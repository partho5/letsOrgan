<?php
/*

*/
class qa_mailgun_admin_config
{
  function admin_form(&$qa_content)
  {
    $saved=false;

    if (qa_clicked('mailgun_save_button')) {
      qa_opt('mailgun_api_enabled', (bool) qa_post_text('mailgun_api_enabled_cb'));
      qa_opt('mailgun_api_key', qa_post_text('mailgun_api_key_field'));
      qa_opt('mailgun_api_domain', qa_post_text('mailgun_api_domain_field'));
      $saved=true;
    }

    return array(
      'ok' => $saved ? 'MailGun API settings saved' : null,

      'fields' => array(
        array(
          'label' => 'Do you want to enable send email via MailGun API?',
          'type' => 'checkbox',
          'value' => (bool)qa_opt('mailgun_api_enabled'),
          'tags' => 'NAME="mailgun_api_enabled_cb"',
        ),
        array(
          'label' => 'MailGun API Key:',
          'value' => qa_html(qa_opt('mailgun_api_key')),
          'tags' => 'name="mailgun_api_key_field"',
        ),
        array(
          'label' => 'Your domain',
          'value' => qa_html(qa_opt('mailgun_api_domain')),
          'tags' => 'name="mailgun_api_domain_field"',
        ),
      ),

      'buttons' => array(
        array(
          'label' => 'Save Changes',
          'tags' => 'name="mailgun_save_button"',
        ),
      ),
    );
  }
}