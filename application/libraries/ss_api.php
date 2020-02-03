<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ss_api {

    var $ci;
    var $schedule_id = "109769";
    var $username = SS_USER;
    var $password = SS_PASSWORD;
    var $checksum;
    var $sort_type = 0;
    var $xml_doc;
    public $accepted_payment_method = array('bank', 'voucher', 'direct');

    function __construct() {
        $this->ci = & get_instance();
    }

    function triggerSendPassword($email) {
        $ch = curl_init();

        $post_fields = "email={$email}&utf8=" . urlencode("✓");

        $url = "http://www.supersaas.com/accounts/lost_password";
        curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);

        curl_exec($ch);
        curl_close($ch);
    }

    function getClassSchedule($class_name = null) {
        $ch = curl_init();
        $rand = rand();
        $this->checksum = md5($this->username . $this->password . $rand);

        $date = new DateTime("now", new DateTimeZone('Asia/Ho_Chi_Minh'));
        $cur_date = $date->format('Y-m-d%20H:i:s');

        $url = "https://www.supersaas.com/api/free/{$this->schedule_id}.xml?from={$cur_date}&checksum={$this->checksum}&user={$rand}&maxresults=100";
        curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, 0);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        $data = curl_exec($ch);
        curl_close($ch);

        $matches = array();
        preg_match("/^(.*?)(<.*)$/s", $data, $matches);

        $header_data = $matches[1];
        if (!strpos($header_data, '200 OK')) {
            die("<div style='font-size:24'>SERVER is busy at the moment. Please refresh your web page. Sorry for the inconvenience.</div>");
        }

        $xml_data = $matches[2];
        $this->xml_doc = simplexml_load_string($xml_data);

        if ($class_name !== null) {
            $xml_nodes = $this->xml_doc->xpath("//slot[title[contains(text(), \"{$class_name}\")]]");
        } else {
            $xml_nodes = $this->xml_doc->xpath("//slot");
        }
        return $xml_nodes;
    }

    function updateUser($id, $updateStr = null) {
        $ch = curl_init();

        $auth = "account={$this->username}&password={$this->password}";

        $url = "https://www.supersaas.com/api/users/{$id}";
        curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $auth . "&" . $updateStr);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    function getUserAgenda($user, $get_newest = false) {
        $ch = curl_init();
        $this->checksum = md5($this->username . $this->password . $user);

        $slot = $get_newest ? '' : '&slot=true';

        $url = "https://www.supersaas.com/api/agenda/{$this->schedule_id}.xml"
                . "?user={$user}"
                . "&checksum={$this->checksum}"
                . "&from=2013-01-01%2000:00:00"
                . $slot;

        curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, 0);
        $data = curl_exec($ch);
        curl_close($ch);

        return $this->xml_doc = simplexml_load_string($data);
    }

    private function get_id($a) {
        return end($a['id']);
    }

    function getNewestBooking($user) {
        $bookings = end($this->getUserAgenda($user));

        if (is_array($bookings)) {
            $max = max(array_map(array($this, 'get_id'), $bookings));

            foreach ($bookings as $booking) {
                if (end($booking['id']) == $max) {
                    $latest_booking = $booking;
                    break;
                }
            }
        } else {
            $latest_booking = $bookings;
        }

        return $latest_booking;
    }

    function getBooking($id) {
        $ch = curl_init();
        $auth = "schedule_id={$this->schedule_id}&password={$this->password}";

        $url = "https://www.supersaas.com/api/bookings/{$id}.xml"
                . "?{$auth}"
                . "&slot=true";

        curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, 0);
        $data = curl_exec($ch);
        curl_close($ch);

        return $this->xml_doc = simplexml_load_string($data);
    }

    public function timeObjCmp($a, $b) {
        switch ($this->sort_type) {
            case 0 :
                $attr = "start";
                break;
            case 1 :
                $attr = "created_on";
                break;
            default :
                $attr = "start";
        }

        if (strtotime($a->$attr) == strtotime($b->$attr)) {
            return 0;
        }
        return (strtotime($a->$attr) > strtotime($b->$attr)) ? -1 : 1;
    }

    public function convertXMLArr2Arr($arr) {
        $new = array();
        foreach ($arr as $item) {
            $date = new DateTime($item->created_on . " UTC");
            $item->created_on = $date->setTimezone(new DateTimeZone('Asia/Saigon'))->format('Y-m-d H:i:s');
            $new[] = $item;
        }
        return $new;
    }

    function getRecentChanges($from = "2013-01-01%2000:00:00") {
        $ch = curl_init();
        $this->checksum = md5($this->username . $this->password);

        $url = "https://www.supersaas.com/api/changes/{$this->schedule_id}.xml"
                . "?checksum={$this->checksum}"
                . "&from={$from}"
                . "&slot=true";


        curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, 0);
        $data = curl_exec($ch);
        curl_close($ch);

        return $this->xml_doc = simplexml_load_string($data);
    }

    function removeBooking($booking_id) {
        $ch = curl_init();
        $auth = "schedule_id={$this->schedule_id}&password={$this->password}";

        $url = "https://www.supersaas.com/api/bookings/{$booking_id}.xml";
        curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $auth);
        $data = curl_setopt($ch, CURLOPT_HEADER, TRUE);

        curl_exec($ch);
        curl_close($ch);

        return true;
    }

    function approveBooking($booking_id) {
        //Payment received by administrator
        $ch = curl_init();
        $booking = $this->getBooking($booking_id);
        $payment_method = $booking->{'super-field'};

        if (!in_array($payment_method, $this->accepted_payment_method)) {
            return;
        }

        $updateStr = "booking[super_field]={$payment_method} (received)";
        $auth = "schedule_id={$this->schedule_id}&password={$this->password}";

        $url = "https://www.supersaas.com/api/bookings/{$booking_id}.xml";
        curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $auth . "&" . $updateStr);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);

        curl_exec($ch);
        curl_close($ch);

        return true;
    }

    function addNotes($booking_id, $notes) {
        //Payment received by administrator
        $ch = curl_init();
        $booking = $this->getBooking($booking_id);
        $old_notes = $booking->{'super-field'};

        if ($old_notes == $notes) {
            return;
        }

        $updateStr = "booking[super_field]={$notes}";
        $auth = "schedule_id={$this->schedule_id}&password={$this->password}";

        $url = "https://www.supersaas.com/api/bookings/{$booking_id}.xml";
        curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $auth . "&" . $updateStr);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);

        curl_exec($ch);
        curl_close($ch);

        return true;
    }

    function updateBooking($user, $payment_method = "credit") {
        $latest_booking = $this->getNewestBooking($user);

        if (!in_array($payment_method, $this->accepted_payment_method)) {
            return;
        }

        $updateStr = "booking[super_field]={$payment_method}";

        $booking_id = $latest_booking['id'];

        $ch = curl_init();

        $auth = "schedule_id={$this->schedule_id}&password={$this->password}";

        $url = "https://www.supersaas.com/api/bookings/{$booking_id}.xml";
        curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $auth . "&" . $updateStr);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);

        curl_exec($ch);
        curl_close($ch);

        $booking_data = $this->getBooking($booking_id);
        $booking_data->title = $latest_booking->slot->title;
        $booking_data->start = $latest_booking->slot->start;

        return $booking_data;
    }

    function deActivateUser($id) {
        return $this->updateUser($id, 'user[role]=' . DEACTIVE_USER);
    }

    function activateUser($id) {
        return $this->updateUser($id, 'user[role]=' . ACTIVE_USER);
    }

}

//        /*for reference :*/
//        ob_start();
//        system("curl -X PUT -d 'user[name]=abc&user[phone]=111' 'http://www.supersaas.com/api/users/1068621?account=chanchin&password=wacontre' -v");
//        $data = ob_get_contents();
//        ob_end_clean();
?>