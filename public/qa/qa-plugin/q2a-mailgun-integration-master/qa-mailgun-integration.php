<?php

function qa_send_email($params)
/*
	Send the email based on the $params array - the following keys are required (some can be empty): fromemail,
	fromname, toemail, toname, subject, body, html
*/
	{

    if(qa_opt('mailgun_api_enabled')){    
      require QA_PLUGIN_DIR.'q2a-mailgun-integration/vendor/autoload.php';
    
      $mgClient = new \Mailgun\Mailgun(qa_opt('mailgun_api_key'));
      $domain = qa_opt('mailgun_api_domain');
    
      $result = $mgClient->sendMessage($domain,
        array('from'    => $params['fromname'] .'<'.$params['fromemail'] .'>',
              'to'      => $params['toname'].'<'.$params['toemail'].'>',
              'subject' => $params['subject'],
              'text'    => $params['body']));
      return $result;
    }
    return
      qa_send_email_base($params);
	}

  
