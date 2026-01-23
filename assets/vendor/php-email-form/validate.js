/**
* PHP Email Form Validation - v3.9
* URL: https://bootstrapmade.com/php-email-form/
* Author: BootstrapMade.com
*/
(function () {
  "use strict";

  let forms = document.querySelectorAll('.php-email-form');

  forms.forEach( function(e) {
    e.addEventListener('submit', function(event) {
      let thisForm = this;
      let action = thisForm.getAttribute('action');
      
      // Check if running on file:// protocol
      if (window.location.protocol === 'file:') {
        // Allow traditional form submission for file:// protocol
        // Don't prevent default, let form submit normally
        // Show message to user
        if (thisForm.querySelector('.error-message')) {
          thisForm.querySelector('.error-message').innerHTML = 'Please run this website on a local server (XAMPP/WAMP) at http://localhost/ for the form to work properly.';
          thisForm.querySelector('.error-message').classList.add('d-block');
        }
        // Allow form to submit normally (will reload page)
        return true; // Don't prevent default
      }
      
      // For http/https protocols, use AJAX
      event.preventDefault();
      
      let recaptcha = thisForm.getAttribute('data-recaptcha-site-key');
      
      if( ! action ) {
        displayError(thisForm, 'The form action property is not set!');
        return;
      }
      thisForm.querySelector('.loading').classList.add('d-block');
      thisForm.querySelector('.error-message').classList.remove('d-block');
      thisForm.querySelector('.sent-message').classList.remove('d-block');

      let formData = new FormData( thisForm );

      if ( recaptcha ) {
        if(typeof grecaptcha !== "undefined" ) {
          grecaptcha.ready(function() {
            try {
              grecaptcha.execute(recaptcha, {action: 'php_email_form_submit'})
              .then(token => {
                formData.set('recaptcha-response', token);
                php_email_form_submit(thisForm, action, formData);
              })
            } catch(error) {
              displayError(thisForm, error);
            }
          });
        } else {
          displayError(thisForm, 'The reCaptcha javascript API url is not loaded!')
        }
      } else {
        php_email_form_submit(thisForm, action, formData);
      }
    });
  });

  function php_email_form_submit(thisForm, action, formData) {
    // Always try to submit to PHP file first
    fetch(action, {
      method: 'POST',
      body: formData,
      headers: {'X-Requested-With': 'XMLHttpRequest'}
    })
    .then(response => {
      if( response.ok ) {
        return response.text();
      } else {
        throw new Error(`${response.status} ${response.statusText}`); 
      }
    })
    .then(data => {
      thisForm.querySelector('.loading').classList.remove('d-block');
      if (data.trim() == 'OK') {
        thisForm.querySelector('.sent-message').classList.add('d-block');
        thisForm.reset(); 
      } else {
        throw new Error(data ? data : 'Form submission failed and no error message returned from: ' + action); 
      }
    })
    .catch((error) => {
      // Only use mailto fallback if it's a network error AND we're on file:// protocol
      if ((error.message.includes('NetworkError') || error.message.includes('Failed to fetch')) && window.location.protocol === 'file:') {
        // Fallback: Use mailto link for local file testing
        const name = formData.get('name') || '';
        const email = formData.get('email') || '';
        const subject = formData.get('subject') || 'Contact Form Submission';
        const message = formData.get('message') || '';
        
        const mailtoLink = `mailto:kaswansunil26@gmail.com?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent('Name: ' + name + '\nEmail: ' + email + '\n\nMessage:\n' + message)}`;
        
        thisForm.querySelector('.loading').classList.remove('d-block');
        window.location.href = mailtoLink;
        thisForm.querySelector('.sent-message').innerHTML = 'Opening email client... If it doesn\'t open, please send email manually to kaswansunil26@gmail.com';
        thisForm.querySelector('.sent-message').classList.add('d-block');
        thisForm.reset();
      } else {
        // Show actual error for server-side issues
        displayError(thisForm, error.message || error);
      }
    });
  }

  function displayError(thisForm, error) {
    thisForm.querySelector('.loading').classList.remove('d-block');
    thisForm.querySelector('.error-message').innerHTML = error;
    thisForm.querySelector('.error-message').classList.add('d-block');
  }

})();
