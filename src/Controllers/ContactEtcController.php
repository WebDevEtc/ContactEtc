<?php namespace WebDevEtc\ContactEtc\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use WebDevEtc\ContactEtc\ContactEtcServiceProvider;
use WebDevEtc\ContactEtc\ContactForm;
use WebDevEtc\ContactEtc\Events\ContactFormError;
use WebDevEtc\ContactEtc\Events\ContactFormSent;
use WebDevEtc\ContactEtc\Events\ContactFormSubmitted;
use WebDevEtc\ContactEtc\FieldGenerator\FieldGeneratorInterface;
use WebDevEtc\ContactEtc\Handlers\HandlerInterface;
use WebDevEtc\ContactEtc\Requests\ContactEtcSubmittedRequest;

/**
 * Class ContactEtcController
 * @package WebDevEtc\ContactEtc\Controllers
 */
class ContactEtcController extends Controller
{
    /** @var  ContactForm - set via $this->getContactForm() */
    protected $contactForm;

    /**
     * Show the requested contact form.
     *
     * @param FieldGeneratorInterface $fieldGenerator
     * @param string $contact_form_name
     * @return mixed
     */
    public function form(
        FieldGeneratorInterface $fieldGenerator,
        $contact_form_name = ContactEtcServiceProvider::DEFAULT_CONTACT_FORM_KEY
    ) {
        $this->getContactForm($fieldGenerator, $contact_form_name);

        // 'please fill out the form:'
        return view('contactetc::form', $this->contactForm->view_params['form_view_vars'])
            ->withFormUrl(route('contactetc.send.' . $contact_form_name))
            ->withContactFormDetails($this->contactForm)
            ->withFields($this->contactForm->fields());
    }

    /**
     * Send the message, and show the confirmation view.
     *
     * @param ContactEtcSubmittedRequest $request
     * @param Mailer $mail
     * @param FieldGeneratorInterface $fieldGenerator
     * @param HandlerInterface $handler
     * @param $contact_form_name
     *
     * @return View|RedirectResponse\
     */
    public function send(
        ContactEtcSubmittedRequest $request,
        Mailer $mail,
        FieldGeneratorInterface $fieldGenerator,
        HandlerInterface $handler,
        string $contact_form_name
    ) {

        $this->getContactForm($fieldGenerator, $contact_form_name);

        event(new ContactFormSubmitted($request->all(), $this->contactForm));

        if (!$handler->handleContactSubmission($mail, $request->all(), $this->contactForm)) {
            return $this->error($request, $handler);
        }

        event(new ContactFormSent($request->all(), $this->contactForm));

        // 'thanks, we will get in touch soon!'
        return view('contactetc::sent', $this->contactForm->view_params['sent_view_vars']);
    }

    /**
     * Send the ContactFormError event, and return a redirectResponse with the old input and any errors from the handler.
     *
     * @param ContactEtcSubmittedRequest $request
     * @param HandlerInterface $handler
     * @return RedirectResponse
     */
    protected function error(ContactEtcSubmittedRequest $request, HandlerInterface $handler)
    {
        event(new ContactFormError($request->all(), $this->contactForm, $handler->getErrors()));

        return back()->withInput()->withErrors($handler->getErrors());
    }

    /**
     * Get the requested ContactForm and set it as a property  on $this.
     *
     * @param FieldGeneratorInterface $fieldGenerator
     * @param string $contact_form_name
     * @return void
     * @throws Exception
     */
    protected function getContactForm(FieldGeneratorInterface $fieldGenerator, string $contact_form_name): void
    {
        $this->contactForm = $fieldGenerator->contactFormNamed($contact_form_name);
    }

}
