import React, { useEffect, useState } from 'react';
import ReactDOM from 'react-dom';

function ChangePassword() {
	const [message, setMessage] = useState('');
	const [messageClass, setMessageClass] = useState('');

	useEffect(() => {
		const form = document.getElementById('change-password-form');

		const handleSubmit = async (e) => {
			e.preventDefault();
			setMessage('');
			setMessageClass('');
			const formData = new FormData(e.target);
			const formButton = e.target.querySelector('.change-password-submit');
			const formButtonDefaultText = formButton.innerHTML;
			formButton.innerHTML = formButton.getAttribute('data-loading-text');

			let data = {};
			let url = '';
			if (formData.get('token')) {
				data = {
					token: formData.get('token'),
					password: formData.get('password'),
					repeat_password: formData.get('repeat-password')
				};
				window.concordamos.rest_url + '/register';
				url = window.concordamos.rest_url + '/change-pass/change';
			} else {
				data = {
					email: formData.get('email')
				};
				url = window.concordamos.rest_url + '/change-pass/generate-token';
			}


			try {
				const response = await fetch(url, {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
						'X-WP-Nonce': window.concordamos.nonce,
					},
					body: JSON.stringify(data),
				});

				const responseData = await response.json();
				setMessage(responseData.message);

				if (response.ok) {
					if (formData.get('token')) {
						setTimeout(() => {
							window.location.href = window.concordamos.login_url
						}, 3000);
					}
					setMessageClass('success');
				} else {
					setMessageClass('error');
				}
			} catch (error) {
				setMessage('An error occurred while processing your request.');
				setMessageClass('error');
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
	const resetFormContainer = document.getElementById('change-password-message');
	if (!resetFormContainer) {
		return;
	}

	ReactDOM.render(<ChangePassword />, resetFormContainer);

	const buttonShowPassword = document.querySelector('button.show-password');
	const inputPassword = document.querySelector('[name="password"]');
	const inputRepeatPassword = document.querySelector('[name="repeat-password"]');
	buttonShowPassword.addEventListener('click', (e) => {
		if (inputPassword.getAttribute('type') == 'password') {
			inputPassword.setAttribute('type', 'text')
			inputRepeatPassword.setAttribute('type', 'text');
		} else {
			inputPassword.setAttribute('type', 'password')
			inputRepeatPassword.setAttribute('type', 'password');
		}
	})

	const fixButtonShowPasswordPosition = () => {
		buttonShowPassword.style.marginLeft = inputPassword.clientWidth - 40 + 'px';
	}
	window.addEventListener('resize', fixButtonShowPasswordPosition);
	setTimeout(() => {
		fixButtonShowPasswordPosition();
	}, 100)
});
