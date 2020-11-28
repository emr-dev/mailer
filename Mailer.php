<?php

/*
 * by EMRDEV
 * web-site: www.emrdev.ru
 * email: dev.emr@yandex.ru
 */


class Mailer
{

    public $to;
    public $ReplyTo;
    public $from;
    public $subject;
    public $html;
    /**
     * CSS стили для тегов письма.
     */
    private $_styles = array(
        'body' => 'margin: 0 0 0 0; padding: 10px 10px 10px 10px; background: #ffffff; color: #000000; font-size: 14px; font-family: Arial, Helvetica, sans-serif; line-height: 18px;',
        'a' => 'color: #003399; text-decoration: underline; font-size: 14px; font-family: Arial, Helvetica, sans-serif; line-height: 18px;',
        'p' => 'margin: 0 0 20px 0; padding: 0 0 0 0; color: #000000; font-size: 14px; font-family: Arial, Helvetica, sans-serif; line-height: 18px;',
        'ul' => 'margin: 0 0 20px 20px; padding: 0 0 0 0; color: #000000; font-size: 14px; font-family: Arial, Helvetica, sans-serif; line-height: 18px;',
        'ol' => 'margin: 0 0 20px 20px; padding: 0 0 0 0; color: #000000; font-size: 14px; font-family: Arial, Helvetica, sans-serif; line-height: 18px;',
        'table' => 'margin: 0 0 20px 0; border: 1px solid #dddddd; border-collapse: collapse;',
        'th' => 'padding: 10px; border: 1px solid #dddddd; vertical-align: middle; background-color: #eeeeee; color: #000000; font-size: 14px; font-family: Arial, Helvetica, sans-serif; line-height: 18px;',
        'td' => 'padding: 10px; border: 1px solid #dddddd; vertical-align: middle; background-color: #ffffff; color: #000000; font-size: 14px; font-family: Arial, Helvetica, sans-serif; line-height: 18px;',
        'h1' => 'margin: 0 0 20px 0; padding: 0 0 0 0; color: #000000; font-size: 22px; font-family: Arial, Helvetica, sans-serif; line-height: 26px; font-weight: bold;',
        'h2' => 'margin: 0 0 20px 0; padding: 0 0 0 0; color: #000000; font-size: 20px; font-family: Arial, Helvetica, sans-serif; line-height: 24px; font-weight: bold;',
        'h3' => 'margin: 0 0 20px 0; padding: 0 0 0 0; color: #000000; font-size: 18px; font-family: Arial, Helvetica, sans-serif; line-height: 22px; font-weight: bold;',
        'h4' => 'margin: 0 0 20px 0; padding: 0 0 0 0; color: #000000; font-size: 16px; font-family: Arial, Helvetica, sans-serif; line-height: 20px; font-weight: bold;',
        'hr' => 'height: 1px; border: none; color: #dddddd; background: #dddddd; margin: 0 0 20px 0;'
    );

    /**
     * @param mixed $to
     */
    public function setTo($to)
    {
        $this->to = $to;
    }

    /**
     * @param mixed $ReplyTo
     */
    public function setReplyTo($ReplyTo)
    {
        $this->ReplyTo[] = $ReplyTo;
    }

    /**
     * @param mixed $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @param mixed $html
     */
    public function setHtml($html)
    {

        $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			</head>
			<body>
				' . $html . '
			</body>
		</html>';
        $this->html = $this->addHtmlStyle($body);
    }


    public function addHtmlStyle($html)
    {
        foreach ($this->_styles as $tag => $style) {
            preg_match_all('/<' . $tag . '([\s].*?)?>/i', $html, $matchs, PREG_SET_ORDER);
            foreach ($matchs as $match) {
                $attrs = array();
                if (!empty($match[1])) {
                    preg_match_all('/[ ]?(.*?)=[\"|\'](.*?)[\"|\'][ ]?/', $match[1], $chanks);
                    if (!empty($chanks[1]) && !empty($chanks[2])) {
                        $attrs = array_combine($chanks[1], $chanks[2]);
                    }
                }

                if (empty($attrs['style'])) {
                    $attrs['style'] = $style;
                } else {
                    $attrs['style'] = rtrim($attrs['style'], '; ') . '; ' . $style;
                }

                $compile = array();
                foreach ($attrs as $name => $value) {
                    $compile[] = $name . '="' . $value . '"';
                }

                $html = str_replace($match[0], '<' . $tag . ' ' . implode(' ', $compile) . '>', $html);
            }
        }

        return $html;
    }


    public function send()
    {
        $headers = 'Content-Type: text/html; charset=UTF-8' .
            "From: $this->from" . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        $status = mail($this->to, $this->subject, $this->html, $headers);
        if (count($this->ReplyTo)) {
            foreach ($this->ReplyTo as $mail) {
                mail($mail, $this->subject, $this->html, $headers);
            }
        }
        return $status;
    }
}
