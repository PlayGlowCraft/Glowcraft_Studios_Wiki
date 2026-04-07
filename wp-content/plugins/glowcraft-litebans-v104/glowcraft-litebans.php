<?php
/*
Plugin Name: GlowCraft LiteBans Integration
Description: Custom LiteBans WordPress integration for GlowCraft Studios, built with assistance from Frank and OpenAI.
Version: 1.0.4
Author: GlowCraft Studios & Frank
*/

if (!defined('ABSPATH')) {
    exit;
}

class GlowCraft_LiteBans_Integration {
    private const OPTION_KEY = 'gcli_litebans_settings';
    private const NONCE_ACTION = 'gcli_litebans_search';
    private const SHORTCODE = 'glowcraft_bans';

    public function __construct() {
        add_action('admin_menu', [$this, 'register_admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_shortcode(self::SHORTCODE, [$this, 'render_shortcode']);
    }

    public function register_admin_menu(): void {
        add_options_page(
            'GlowCraft LiteBans',
            'GlowCraft LiteBans',
            'manage_options',
            'glowcraft-litebans',
            [$this, 'render_settings_page']
        );
    }

    public function register_settings(): void {
        register_setting('gcli_litebans_group', self::OPTION_KEY, [$this, 'sanitize_settings']);

        add_settings_section(
            'gcli_main_section',
            'Database Settings',
            function () {
                echo '<p>Enter the LiteBans database details used by your Minecraft server.</p>';
            },
            'glowcraft-litebans'
        );

        $fields = [
            'db_host' => 'Database Host',
            'db_name' => 'Database Name',
            'db_user' => 'Database Username',
            'db_pass' => 'Database Password',
            'table_prefix' => 'Table Prefix',
            'results_per_tab' => 'Rows Per Tab',
        ];

        foreach ($fields as $key => $label) {
            add_settings_field(
                $key,
                $label,
                [$this, 'render_settings_field'],
                'glowcraft-litebans',
                'gcli_main_section',
                ['key' => $key, 'label' => $label]
            );
        }
    }

    public function sanitize_settings(array $input): array {
        return [
            'db_host' => sanitize_text_field($input['db_host'] ?? 'localhost'),
            'db_name' => sanitize_text_field($input['db_name'] ?? ''),
            'db_user' => sanitize_text_field($input['db_user'] ?? ''),
            'db_pass' => (string)($input['db_pass'] ?? ''),
            'table_prefix' => sanitize_text_field($input['table_prefix'] ?? 'litebans_'),
            'results_per_tab' => max(1, min(100, intval($input['results_per_tab'] ?? 10))),
        ];
    }

    public function render_settings_field(array $args): void {
        $settings = $this->get_settings();
        $key = $args['key'];
        $value = $settings[$key] ?? '';
        $type = $key === 'db_pass' ? 'password' : ($key === 'results_per_tab' ? 'number' : 'text');
        $placeholder = '';

        if ($key === 'table_prefix') {
            $placeholder = 'litebans_';
        }

        printf(
            '<input type="%1$s" name="%2$s[%3$s]" value="%4$s" class="regular-text" placeholder="%5$s" %6$s />',
            esc_attr($type),
            esc_attr(self::OPTION_KEY),
            esc_attr($key),
            esc_attr($value),
            esc_attr($placeholder),
            $type === 'number' ? 'min="1" max="100"' : ''
        );

        if ($key === 'table_prefix') {
            echo '<p class="description">Usually <code>litebans_</code>. Example tables: <code>litebans_bans</code>, <code>litebans_history</code>.</p>';
        }
    }

    public function render_settings_page(): void {
        if (!current_user_can('manage_options')) {
            return;
        }
        ?>
        <div class="wrap">
            <h1>GlowCraft LiteBans</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('gcli_litebans_group');
                do_settings_sections('glowcraft-litebans');
                submit_button();
                ?>
            </form>
            <hr />
            <p><strong>Shortcode:</strong> <code>[glowcraft_bans]</code></p>
            <p>Place the shortcode on your WordPress bans page.</p>
        </div>
        <?php
    }

    public function enqueue_assets(): void {
        wp_register_style(
            'gcli-litebans-style',
            plugins_url('assets/glowcraft-litebans.css', __FILE__),
            [],
            '1.0.4'
        );
    }

    public function render_shortcode(): string {
        wp_enqueue_style('gcli-litebans-style');

        $settings = $this->get_settings();
        $db = $this->connect_db($settings);

        if (is_wp_error($db)) {
            return $this->render_notice('LiteBans database connection failed. Check the plugin settings.', 'error');
        }

        $search = isset($_GET['gcb_player']) ? sanitize_text_field(wp_unslash($_GET['gcb_player'])) : '';
        $search = trim($search);
        $limit = intval($settings['results_per_tab']);
        $prefix = $settings['table_prefix'];

        $tabs = [
            'bans' => ['label' => 'Bans', 'table' => $prefix . 'bans'],
            'mutes' => ['label' => 'Mutes', 'table' => $prefix . 'mutes'],
            'warnings' => ['label' => 'Warnings', 'table' => $prefix . 'warnings'],
            'kicks' => ['label' => 'Kicks', 'table' => $prefix . 'kicks'],
        ];

        $history_table = $prefix . 'history';
        $active_tab = isset($_GET['gcb_tab']) ? sanitize_key(wp_unslash($_GET['gcb_tab'])) : 'bans';
        if (!isset($tabs[$active_tab])) {
            $active_tab = 'bans';
        }

        ob_start();
        ?>
        <div class="gcli-wrap">
            <div class="gcli-hero">
                <h2>GlowCraft Punishment Records</h2>
                <p>Search for a player or browse the most recent records below.</p>
            </div>

            <form class="gcli-search" method="get">
                <?php $this->preserve_non_plugin_query_vars(); ?>
                <input type="hidden" name="gcb_tab" value="<?php echo esc_attr($active_tab); ?>" />
                <label class="screen-reader-text" for="gcb_player">Search by player</label>
                <input type="text" id="gcb_player" name="gcb_player" value="<?php echo esc_attr($search); ?>" placeholder="Search by player name" />
                <button type="submit">Search</button>
                <?php if ($search !== ''): ?>
                    <a class="gcli-clear" href="<?php echo esc_url($this->base_url_without_plugin_args()); ?>">Clear</a>
                <?php endif; ?>
            </form>

            <div class="gcli-tabs">
                <?php foreach ($tabs as $slug => $tab): ?>
                    <?php $tab_url = add_query_arg(['gcb_tab' => $slug] + ($search !== '' ? ['gcb_player' => $search] : []), $this->base_url_without_plugin_args()); ?>
                    <a class="gcli-tab <?php echo $slug === $active_tab ? 'is-active' : ''; ?>" href="<?php echo esc_url($tab_url); ?>"><?php echo esc_html($tab['label']); ?></a>
                <?php endforeach; ?>
            </div>

            <?php
            if ($search !== '') {
                echo $this->render_player_summary($db, $history_table, $tabs, $search, $limit);
            }

            $current = $tabs[$active_tab];
            $rows = $this->fetch_punishment_rows($db, $current['table'], $search, $limit);
            if (!empty($db->last_error) && current_user_can('manage_options')) {
                echo $this->render_notice('LiteBans query error: ' . $db->last_error, 'error');
            }
            echo $this->render_table($db, $rows, $current['label']);
            ?>
        </div>
        <?php
        return (string) ob_get_clean();
    }

    private function preserve_non_plugin_query_vars(): void {
        foreach ($_GET as $key => $value) {
            if (strpos((string) $key, 'gcb_') === 0) {
                continue;
            }
            if (is_array($value)) {
                continue;
            }
            printf(
                '<input type="hidden" name="%1$s" value="%2$s" />',
                esc_attr((string) $key),
                esc_attr(sanitize_text_field(wp_unslash((string) $value)))
            );
        }
    }

    private function base_url_without_plugin_args(): string {
        $url = home_url(add_query_arg([], $GLOBALS['wp']->request ?? ''));
        return remove_query_arg(['gcb_tab', 'gcb_player'], $url);
    }

    private function get_settings(): array {
        $defaults = [
            'db_host' => 'localhost',
            'db_name' => '',
            'db_user' => '',
            'db_pass' => '',
            'table_prefix' => 'litebans_',
            'results_per_tab' => 10,
        ];

        $settings = get_option(self::OPTION_KEY, []);
        if (!is_array($settings)) {
            $settings = [];
        }

        return wp_parse_args($settings, $defaults);
    }

    private function connect_db(array $settings) {
        if ($settings['db_name'] === '' || $settings['db_user'] === '') {
            return new WP_Error('gcli_missing_settings', 'Missing database settings.');
        }

        $db = @new wpdb(
            $settings['db_user'],
            $settings['db_pass'],
            $settings['db_name'],
            $settings['db_host']
        );

        if (!empty($db->error)) {
            return new WP_Error('gcli_db_error', $db->error->get_error_message());
        }

        $db->hide_errors();
        return $db;
    }

    private function render_player_summary(wpdb $db, string $history_table, array $tabs, string $player, int $limit): string {
        $safe_like = '%' . $db->esc_like($player) . '%';
        $history_rows = $db->get_results(
            $db->prepare(
                "SELECT name, uuid, ip, date FROM {$history_table} WHERE name LIKE %s ORDER BY date DESC LIMIT %d",
                $safe_like,
                $limit
            )
        );

        $counts = [];
        foreach ($tabs as $key => $tab) {
            $counts[$key] = (int) $db->get_var(
                $db->prepare(
                    "SELECT COUNT(*) FROM {$tab['table']} WHERE uuid IN (SELECT uuid FROM {$history_table} WHERE name LIKE %s) OR ip IN (SELECT ip FROM {$history_table} WHERE name LIKE %s)",
                    $safe_like,
                    $safe_like
                )
            );
        }

        ob_start();
        ?>
        <div class="gcli-card gcli-player-summary">
            <div>
                <h3>Search Results for <?php echo esc_html($player); ?></h3>
                <?php if (!empty($history_rows)): ?>
                    <p class="gcli-muted">Most recent known names and identifiers found in LiteBans history.</p>
                <?php else: ?>
                    <p class="gcli-muted">No player history records matched that search.</p>
                <?php endif; ?>
            </div>
            <div class="gcli-stat-grid">
                <div class="gcli-stat"><span>Bans</span><strong><?php echo esc_html((string) $counts['bans']); ?></strong></div>
                <div class="gcli-stat"><span>Mutes</span><strong><?php echo esc_html((string) $counts['mutes']); ?></strong></div>
                <div class="gcli-stat"><span>Warnings</span><strong><?php echo esc_html((string) $counts['warnings']); ?></strong></div>
                <div class="gcli-stat"><span>Kicks</span><strong><?php echo esc_html((string) $counts['kicks']); ?></strong></div>
            </div>
            <?php if (!empty($history_rows)): ?>
                <div class="gcli-history-list">
                    <?php foreach ($history_rows as $row): ?>
                        <div class="gcli-history-item">
                            <strong><?php echo esc_html((string) $row->name); ?></strong>
                            <span>UUID: <?php echo esc_html((string) $row->uuid); ?></span>
                            <span>IP: <?php echo esc_html((string) $row->ip); ?></span>
                            <span>Seen: <?php echo esc_html($this->format_sql_datetime((string) $row->date)); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
        return (string) ob_get_clean();
    }

    private function fetch_punishment_rows(wpdb $db, string $table, string $player, int $limit): array {
        $history_table = $this->get_settings()['table_prefix'] . 'history';

        $player_name_sql = "(SELECT h.name FROM {$history_table} h WHERE h.uuid = p.uuid AND h.name <> '' ORDER BY h.date DESC LIMIT 1)";
        $player_ip_name_sql = "(SELECT h.name FROM {$history_table} h WHERE h.ip = p.ip AND h.name <> '' ORDER BY h.date DESC LIMIT 1)";
        $staff_name_sql = "(SELECT h.name FROM {$history_table} h WHERE h.uuid = p.banned_by_uuid AND h.name <> '' ORDER BY h.date DESC LIMIT 1)";

        $base_select = "SELECT p.*, COALESCE({$player_name_sql}, {$player_ip_name_sql}) AS resolved_player_name, {$staff_name_sql} AS resolved_staff_name FROM {$table} p";

        if ($player === '') {
            $query = $db->prepare("{$base_select} ORDER BY p.time DESC LIMIT %d", $limit);
            $rows = $db->get_results($query);
            if (is_array($rows) && empty($db->last_error)) {
                return $rows;
            }

            $fallback = $db->prepare("SELECT * FROM {$table} ORDER BY time DESC LIMIT %d", $limit);
            $rows = $db->get_results($fallback);
            return is_array($rows) ? $rows : [];
        }

        $safe_like = '%' . $db->esc_like($player) . '%';
        $query = $db->prepare(
            "{$base_select}
             WHERE p.uuid IN (SELECT uuid FROM {$history_table} WHERE name LIKE %s)
             OR p.ip IN (SELECT ip FROM {$history_table} WHERE name LIKE %s)
             OR {$player_name_sql} LIKE %s
             OR {$player_ip_name_sql} LIKE %s
             ORDER BY p.time DESC
             LIMIT %d",
            $safe_like,
            $safe_like,
            $safe_like,
            $safe_like,
            $limit
        );
        $rows = $db->get_results($query);
        if (is_array($rows) && empty($db->last_error)) {
            return $rows;
        }

        $fallback = $db->prepare(
            "SELECT * FROM {$table}
             WHERE uuid IN (SELECT uuid FROM {$history_table} WHERE name LIKE %s)
             OR ip IN (SELECT ip FROM {$history_table} WHERE name LIKE %s)
             ORDER BY time DESC
             LIMIT %d",
            $safe_like,
            $safe_like,
            $limit
        );
        $rows = $db->get_results($fallback);
        return is_array($rows) ? $rows : [];
    }

    private function get_row_player_name(wpdb $db, object $row): string {
        if (!empty($row->resolved_player_name)) {
            return (string) $row->resolved_player_name;
        }

        if (!empty($row->name)) {
            return (string) $row->name;
        }

        $prefix = $this->get_settings()['table_prefix'];
        $history_table = $prefix . 'history';

        if (!empty($row->uuid)) {
            $name = $db->get_var(
                $db->prepare(
                    "SELECT name FROM {$history_table} WHERE uuid = %s AND name <> '' ORDER BY date DESC LIMIT 1",
                    $row->uuid
                )
            );
            if (!empty($name)) {
                return (string) $name;
            }
        }

        if (!empty($row->ip)) {
            $name = $db->get_var(
                $db->prepare(
                    "SELECT name FROM {$history_table} WHERE ip = %s AND name <> '' ORDER BY date DESC LIMIT 1",
                    $row->ip
                )
            );
            if (!empty($name)) {
                return (string) $name;
            }
        }

        return (string) ($row->uuid ?? 'Unknown Player');
    }

    private function get_row_staff_name(object $row, string $label): string {
        $staff_fields = [
            'banned_by_name',
            'muted_by_name',
            'warned_by_name',
            'kicked_by_name',
            'actor_name',
            'resolved_staff_name',
        ];

        foreach ($staff_fields as $field) {
            if (!empty($row->$field)) {
                return (string) $row->$field;
            }
        }

        return 'Console';
    }

    private function render_table(wpdb $db, array $rows, string $label): string {
        ob_start();
        ?>
        <div class="gcli-card">
            <div class="gcli-card-header">
                <h3>Recent <?php echo esc_html($label); ?></h3>
            </div>
            <div class="gcli-table-wrap">
                <table class="gcli-table">
                    <thead>
                        <tr>
                            <th>Player / UUID</th>
                            <th>Reason</th>
                            <th>Staff</th>
                            <th>Status</th>
                            <th>Issued</th>
                                                    </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($rows)): ?>
                        <tr>
                            <td colspan="5" class="gcli-empty">No records found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($rows as $row): ?>
                            <?php $player_name = $this->get_row_player_name($db, $row); ?>
                            <?php $staff_name = $this->get_row_staff_name($row, $label); ?>
                            <tr>
                                <td>
                                    <div class="gcli-player-cell">
                                        <?php if (!empty($row->uuid)): ?>
                                            <img class="gcli-player-avatar" src="https://mc-heads.net/avatar/<?php echo esc_attr(str_replace('-', '', (string) $row->uuid)); ?>/48" alt="" loading="lazy" decoding="async" referrerpolicy="no-referrer" />
                                        <?php endif; ?>
                                        <div class="gcli-player-meta">
                                            <strong><?php echo esc_html($player_name); ?></strong>
                                            <?php if (!empty($row->uuid)): ?>
                                                <div class="gcli-subtext">UUID: <?php echo esc_html((string) $row->uuid); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo wp_kses_post($this->format_reason_text((string) ($row->reason ?? 'No reason provided'))); ?></td>
                                <td><?php echo esc_html($staff_name); ?></td>
                                <td><?php echo wp_kses_post($this->status_badge($row)); ?></td>
                                <td><?php echo esc_html($this->format_millis($row->time ?? null)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
        return (string) ob_get_clean();
    }


    private function format_reason_text(string $text): string {
        if ($text === '') {
            return 'No reason provided';
        }

        $map = [
            '0' => '#000000',
            '1' => '#0000AA',
            '2' => '#00AA00',
            '3' => '#00AAAA',
            '4' => '#AA0000',
            '5' => '#AA00AA',
            '6' => '#FFAA00',
            '7' => '#AAAAAA',
            '8' => '#555555',
            '9' => '#5555FF',
            'a' => '#55FF55',
            'b' => '#55FFFF',
            'c' => '#FF5555',
            'd' => '#FF55FF',
            'e' => '#FFFF55',
            'f' => '#FFFFFF',
        ];

        $out = '';
        $open = false;
        $len = strlen($text);

        for ($i = 0; $i < $len; $i++) {
            $char = $text[$i];
            if (($char === '&' || $char == "§") && $i + 1 < $len) {
                $code = strtolower($text[$i + 1]);
                if (isset($map[$code])) {
                    if ($open) {
                        $out .= '</span>';
                    }
                    $out .= '<span style="color:' . $map[$code] . ';">';
                    $open = true;
                    $i++;
                    continue;
                }
                if (in_array($code, ['r', 'l', 'm', 'n', 'o', 'k'], true)) {
                    if ($code === 'r' && $open) {
                        $out .= '</span>';
                        $open = false;
                    }
                    $i++;
                    continue;
                }
            }
            $out .= esc_html($char);
        }

        if ($open) {
            $out .= '</span>';
        }

        return $out;
    }

    private function status_badge(object $row): string {
        $active = isset($row->active) ? (int) $row->active === 1 : false;
        if ($active) {
            return '<span class="gcli-badge is-active">Active</span>';
        }
        return '<span class="gcli-badge">Inactive</span>';
    }

    private function format_millis($value): string {
        if ($value === null || $value === '' || !is_numeric($value)) {
            return '—';
        }
        $timestamp = (int) floor(((int) $value) / 1000);
        if ($timestamp <= 0) {
            return '—';
        }
        return wp_date('Y-m-d g:i A', $timestamp);
    }

    private function format_until($value): string {
        if ($value === null || $value === '' || !is_numeric($value)) {
            return '—';
        }
        $until = (int) $value;
        if ($until < 0) {
            return 'Permanent';
        }
        return $this->format_millis($until);
    }

    private function format_sql_datetime(string $value): string {
        $timestamp = strtotime($value);
        if ($timestamp === false) {
            return $value;
        }
        return wp_date('Y-m-d g:i A', $timestamp);
    }

    private function render_notice(string $message, string $type = 'info'): string {
        return sprintf(
            '<div class="gcli-card"><p class="gcli-notice gcli-notice-%1$s">%2$s</p></div>',
            esc_attr($type),
            esc_html($message)
        );
    }
}

new GlowCraft_LiteBans_Integration();
