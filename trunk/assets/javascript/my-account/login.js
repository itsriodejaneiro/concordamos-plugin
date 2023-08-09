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

      const data = {
        username: formData.get('email'),
        password: formData.get('password')
      };

      const url = window.location.origin + '/wp-json/concordamos/my-account/v1/login';
      
      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
      });

      const responseData = await response.json();
      console.log( responseData );
      setMessage(responseData.message);
      
      if (response.ok) {
        setMessageClass('success');
        setTimeout(() => {
          window.location.reload();
        }, 10000);
      } else {
        setMessageClass('error')
      }
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
  if (loginFormContainer) {
    ReactDOM.render(<Login />, loginFormContainer);
  }
});
