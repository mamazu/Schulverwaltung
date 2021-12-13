<?php

namespace Mamazu\Schulverwaltung\Modules\Mail;

class Mail
{
	private $sender, $subject, $content, $sendDate, $read, $deleted;

	public function __construct($sender = null, $subject = null, $content = null, $read = false, $sendDate = null, $deleteDate = null)
	{
		$this->sender = ($sender == '') ? 0 : $sender;
		$this->subject = $subject;
		$this->content = $content;
		$this->read = $read;
		$this->sendDate = strtotime($sendDate);
		$this->deleted = ($deleteDate == null) ? null : strtotime($deleteDate);
	}

    public function getMessageAge(): string {
        if ($this->getDeleteDate()) {
            return $this->isRead() ? getTimePassed($this->getSendDate(), true) : '*';
        }

        return getTimePassed($this->getDeleteDate(), true);
    }

	public function load($id)
	{
		$dbId = intval($id);
		global $database;
		$result = $database->query("SELECT sender, subject, content, readStatus, sendDate, deleted FROM user__messages WHERE id = $dbId;");
		$row = $result->fetch_assoc();
		$this->sender = $row['sender'];
		$this->subject = $row['subject'];
		$this->content = $row['content'];
		$this->read = $row['readStatus'];
		$this->sendDate = strtotime($row['sendDate']);
		$this->deleted = is_null($row['deleted']) ? null : strtotime($row['deleted']);
	}

	// <editor-fold defaultstate="collapsed" desc="Getter of the private properties">
	public function getSender()
	{
		return $this->sender;
	}

	public function getSubject()
	{
		return $this->subject;
	}

	public function getContent()
	{
		return nl2br($this->content);
	}

	public function getSendDate()
	{
		return $this->sendDate;
	}

	public function isRead()
	{
		return $this->read;
	}

	public function getDeleteDate()
	{
		return $this->deleted;
	}

	// </editor-fold>


	public function setRead($id)
	{
		$dbId = intval($id);
		global $database;
		$result = $database->query("UPDATE user__messages SET readStatus = TRUE WHERE id = $dbId;");
		if ($database->errno != 0) {
			$this->read = true;
		}
		return $result;
	}

}

function getTimePassed($timestr, $short = false)
{
	$descr = time() - $timestr;
	if ($descr < 0) {
		return ($short) ? '--' : 'back in time';
	}
	if ($descr < 60) {
		return ($short) ? '<1 m' : 'less than a minute ago';
	}
	if ($descr < 3600) {
		return round($descr / 60) . (($short) ? ' min' : ' minutes ago');
	}
	if ($descr < 86400) {
		return round($descr / 3600) . (($short) ? ' h' : ' hours ago');
	}
	if ($descr < 31536000) {
		return round($descr / 86400) . (($short) ? ' d' : ' days ago');
	}
	return round($descr / 31536000, 1) . (($short) ? ' y' : ' years ago');
}
