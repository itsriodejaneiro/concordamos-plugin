import React, { useEffect, useState } from 'react';
import ReactDOM from 'react-dom';

function Login() {
  const [message, setMessage] = useState('');
  const [messageClass, setMessageClass] = useState('');

  useEffect(() => {
    const form = document.getElementById('login-form');

    const handleSubmit = async (e) => {
      e.preventDefault();
      setMessage('');
      setMessageClass('');
      const formData = new FormData(e.target);
      const formButton = e.target.querySelector('.login-submit');
      const formButtonDefaultText = formButton.innerHTML;
      formButton.innerHTML = formButton.getAttribute('data-loading-text');

      const data = {
        username: formData.get('email'),
        password: formData.get('password')
      };

      const url = window.location.origin + '/wp-json/concordamos/user/v1/login';
      
      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-WP-Nonce': window.concordamos_login.nonce,
        },
        body: JSON.stringify(data),
      });

      const responseData = await response.json();
      setMessage(responseData.message);
      
      if (response.ok) {
        setMessageClass('success');
        setTimeout(() => {
          window.location.href = window.concordamos_login.my_account_url
        }, 3000);
      } else {
        setMessageClass('error')
      }
      formButton.innerHTML = formButtonDefaultText;
    };

    form.addEventListener('submit', handleSubmit);

    return () => {
      form.removeEventListener('submit', handleSubmit);
    };
  }, []);

  return (
    <div className={messageClass}>
      <p>{message}</p>
    </div>
  );
}

window.addEventListener('DOMContentLoaded', () => {
  const loginFormContainer = document.getElementById('login-message');
  if ( ! loginFormContainer ) {
    return;
  }
  
  ReactDOM.render(<Login />, loginFormContainer);
  
  const buttonShowPassword = document.querySelector('button.show-password');
  buttonShowPassword.addEventListener('click', (e) => {
    const inputPassword = document.querySelector('[name="password"]');
    if( inputPassword.getAttribute( 'type') == 'password' ) {
      inputPassword.setAttribute('type', 'text' )
    } else {
      inputPassword.setAttribute('type', 'password' )
    }
  })

});