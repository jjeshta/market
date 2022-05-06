<?php

namespace Drupal\market_events\Form;

use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\node\Entity\Node;

class CreateEventForm extends FormBase
{
    /**
     * {@inheritdoc}
     */

    public function getFormId()
    {
        return 'create_event_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $form['event_title'] = [
            '#title' => t('Event title'),
            '#type' => 'textfield',
            '#size' => 30,
            '#prefix' => '<div id="title_validator">',
            '#suffix' => '</div>',
            '#required' => true,
            // '#ajax' => [
            //     'callback' => '::validate_event_title',
            //     'wrapper' => 'title_validator',
            //     'event' => 'change',
            //     'progress' => [
            //         'type' => 'throbber',
            //         'message' => t('Verifying title...'),
            //     ],
            // ],

            '#placeholder' => t("Enter your event title"),
        ];

        $form['description'] = [
            '#title' => t('Event Description'),
            '#type' => 'textfield',
            '#size' => 50,
            '#prefix' => '<div id="desc_validator">',
            '#suffix' => '</div>',
            '#placeholder' => t("Enter your event description"),
            '#ajax' => [
                'callback' => '::validate_event_desc',
                'wrapper' => 'desc_validator',
                'event' => 'change',
                'progress' => [
                    'type' => 'throbber',
                    'message' => t('Verifying title...'),
                ],
            ],

            '#required' => true,
        ];

        $form['hosted_on'] = [
            '#title' => t('Hosted On'),
            '#type' => 'datetime',
            '#size' => 20,
            '#required' => true,
        ];

        $form['email'] = [
            '#title' => t('Contact person email'),
            '#type' => 'textfield',
            '#size' => 40,
            '#placeholder' => t("example@org.com"),
            '#required' => true,
        ];

        $validators = array(
            'file_validate_extensions' => array('jpg jpeg gif png'),
        );

        $form['upload_file'] = [
            '#type' => 'managed_file',
            '#name' => 'upload_file',
            '#title' => $this->t('Event Photo'),
            '#upload_validators' => $validators,
            '#upload_location' => 'public://',
        ];

        $form['agree'] = [
            '#type' => 'checkbox',
            '#title' => $this->t('Agree with terms and conditions'),
            '#description' => $this->t('Terms and conditions lorem ipsum dolore sit amet und'),
        ];

        $form['submit'] = array(
            '#type' => 'submit',
            '#value' => t('Create Event'),
        );

        return $form;

    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $title = Html::escape($form_state->getValue('event_title'));
        $desc = Html::escape($form_state->getValue('description'));
        $hosted_on = $form_state->getValue('hosted_on');
        $email = Xss::filter($form_state->getValue('email'));

        /**
         *  $form_state->getValue('upload_file', 0); will return an
         * array (size=1)
         * 0 => string '5' (length=1)
         *
         * $form_state->getValue('upload_file', 0);
         * Note 0 is added as default meaning The default value if the specified key does not exist.
         */

        $form_data = $form_state->getValue('upload_file', 0);

        $node = Node::create(['type' => 'event']);
        $node->title = $title;
        $node->field_event_description = $desc;
        $node->field_event_on = $hosted_on;
        $node->field_contact_person_email = $email;

        //file uploaded ok
        if ($form_data > 0) {

            try {
                $file = File::load($form_data[0]);
                $file->setPermanent();
                $file->save();

                $node->set('field_event_image', ['target_id' => $file->id()]);

            } catch (\Throwable $th) {
                //throw $th;
            }
        }

        $node->save();

    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        if ($form_state->getValue('upload_file') == null) {
            $form_state->setErrorByName('upload_file', $this->t('File.'));
        }
    }

    public function validate_event_desc(array &$form, FormStateInterface $form_state)
    {
        $response = new AjaxResponse();
        $output = '';
        if (len($form_state->getValue('description') < 10)) {
            $output = 'Enter valid description';
        }
        return $response->addCommand(new AppendCommand('#desc_validator', $output));
    }

    public function validate_event_title(array &$form, FormStateInterface $form_state)
    {
        $response = new AjaxResponse();
        $output = '';
        if (len($form_state->getValue('event_title') < 3)) {
            $output = 'Enter valid title';
        }
        return $response->addCommand(new AppendCommand('#title_validator', $output));
    }

}