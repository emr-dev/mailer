# Mailer
PHP class for sending E-mail
 
## Using  

```
  $mail = new Mailer();
  $mail->setFrom('some_mail');
  $mail->setTo('some_mail');
  $mail->setReplyTo('some_mail');
  $mail->setReplyTo('dev.emr@yandex.ru');
  $mail->setSubject('SUBJECT');
  $mail->setHtml($html);
  $mail->send()
  
``` 
