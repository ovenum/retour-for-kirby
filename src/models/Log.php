<?php

namespace distantnative\Retour;

use Kirby\Database\Db;

class Log
{

    public static $file;

    /**
     * Connect to database on initialization
     */
    public function __construct()
    {
        Db::connect([
            'type'=> 'sqlite',
            'database' => self::$file
        ]);
    }

    /**
     * Create a new record entry in database
     *
     * @param array $props
     * @return void
     */
    public function add(array $props)
    {
        Db::insert('records', [
            'date'     => $props['date'] ?? date('Y-m-d H:i:s'),
            'path'     => $props['path'],
            'referrer' => $props['referrer'] ?? null,
            'redirect' => $props['redirect'] ?? null
        ]);
    }

    /**
     * Close database connection
     *
     * @return void
     */
    public function close()
    {
        Db::$connection = null;
    }

    /**
     * Remove database records and reset index
     *
     * @return void
     */
    public function flush()
    {
        Db::query('DELETE FROM records;');
        Db::query('DELETE FROM sqlite_sequence WHERE name="records";');
    }

    /**
     * Get all failed records
     *
     * @return array
     */
    public function forFails(): array
    {
        return Db::query('
            SELECT
                id,
                path,
                referrer,
                MAX(date) AS last,
                COUNT(date) AS hits
            FROM
                records
            WHERE
                redirect IS NULL AND
                wasResolved IS NULL
            GROUP BY
                path,
                referrer;
        ')->toArray();
    }

    /**
     * Get all records for a redirect
     *
     * @param array $redirect
     * @return array
     */
    public function forRedirect(array $redirect): array
    {
        $data = Db::first('records', '
            COUNT(*) AS hits,
            MAX(date) AS last'
        , 'redirect="' . $redirect['from'] . '"');

        return array_merge($redirect, [
            'hits' => $data->hits(),
            'last' => $data->last()
        ]);
    }

    /**
     * Return aggregated data as timeline
     *
     * @param string $start
     * @param string $end
     * @param string $label
     * @return array
     */
    public function forStats(string $start, string $end, string $label = 'd'): array
    {
        return Db::query('
            SELECT
                strftime("%' . $label . '", date) AS label,
                strftime("%s", date) AS time,
                COUNT(path) AS total,
                COUNT(wasResolved) AS resolved,
                COUNT(redirect) AS redirected
            FROM
                records
            WHERE
                strftime("%s", date) > strftime("%s", "' . $start . '") AND
                strftime("%s", date) < strftime("%s", "' . $end . '")
            GROUP BY
                label;
        ')->toArray();
    }

    public function limit()
    {
        $limit  = option('distantnative.retour.deleteAfter', false);

        if ($limit) {
            $time   = strtotime('-' . $limit . ' month');
            $cutoff = date('Y-m-d 00:00:00', $time);

            Db::query('
                DELETE FROM
                    records
                WHERE
                    strftime("%s", date) < strftime("%s", "' . $cutoff . '");
            ');
        }
    }

    /**
     * Mark all records for a given path as resolved
     *
     * @param string $path
     * @return bool
     */
    public function resolve(string $path): bool
    {
        return Db::update('records', ['wasResolved' => 1], ['path' => $path]);
    }

}
