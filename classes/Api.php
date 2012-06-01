<?php
// http://www.activecollab.com/docs/manuals/developers/api/time#add-time-record
class Api
{
    private $_key;
    private $_url;
    private $_format = 'json';

    public function __construct(array $settings)
    {
        $this->_key = trim($settings['key']);
        $this->_url = trim($settings['url']);
    }

    // Getters
    public function getUrl() { return $this->_url; }
    public function getKey() { return $this->_key; }
    public function getFormat() { return $this->_format; }


    public function testConnection()
    {
        $res = $this->read('info');
        return $res !== false;
    }

    public function get($section)
    {
        $res = $this->read($section);
        if ($res === false) {
            $res = '';
        }
        return json_decode($res, true);
    }

    public function logTime($userId, $projectId, $time, $desc, $date, $billable = 1, $ticketId = false)
    {
        if (empty($userId) || empty($time) || empty($date) || empty($projectId) || empty($desc)) {
            return false;
        }

        // Format
        $userId = (int) $userId;
        $time = trim($time);
        $desc = trim($desc);
        $date = trim($date);
        $projectId = (int) $projectId;
        if ($ticketId !== false) {
            $ticketId = (int) $ticketId;
        }

        $params = array(
            'submitted'             => 'submitted', // Need this for active collab
            'time[value]'           => $time,
            'time[user_id]'         => $userId,
            'time[record_date]'     => $date,
            'time[body]'            => '',
            'time[billable_status]' => (!empty($billable) ? 1 : 0)
        );

        // Add optional
        if (!empty($desc)) {
            $params['time[body]'] = $desc;
        }
        if (!empty($ticketId)) {
            $params['time[parent_id]'] = $this->getRealTicketId($projectId, $ticketId);
        }

        // Call api
        $res = $this->read(
            'projects/' . (int) $projectId . '/time/add',
            'POST',
            $params
        );

        if (empty($res)) {
            return false;
        }
        return json_decode($res);
    }

    private function getRealTicketId($projectId, $ticketId)
    {
        $res = $this->read("projects/$projectId/tickets");
        if ($res !== false) {
            $ticket = json_decode($res, true);
            foreach ($ticket as $t) {
                if ($t['ticket_id'] == $ticketId) {
                    return $t['id'];
                }
            }
        }
        return '';
    }

    private function read($path = 'projects', $method = 'GET', array $params = array())
    {
        if (!is_string($path) || empty($path)) {
            return false;
        }
        // Build params
        $params = http_build_query($params);

        // Set url
        $url = "{$this->getUrl()}?token={$this->getKey()}&format={$this->getFormat()}&path_info=$path";

        // Method based
        switch ($method)
        {
            default:
                $opts = array(
                    'http' => array(
                        'method'  => $method
                    )
                );

                // Create
                $context = stream_context_create($opts);

                // Check HTTP STATUS CODE in headers for GET
                $headers = get_headers($url);
                if ($this->goodHeaders($headers) === false) {
                     return false;
                }

                // Read
                return file_get_contents($url, false, $context);
                break;

            case 'POST':
                // Curl on post, otherwise: 400 Bad Request X_x
                $curl;
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $response = curl_exec($ch);
                curl_close($ch);
                if (!empty($response)) {
                    return $response;
                }
                break;
        }
    }

    private function goodHeaders(array $headers, $protocol = 'HTTP/1.1')
    {
        $good = false;
        foreach ($headers as $header)
        {
            if (strpos($header, $protocol . ' 3') !== false || strpos($header, $protocol . ' 2') !== false) {
                $good = true;
            }
        }
        return $good;
    }
}
