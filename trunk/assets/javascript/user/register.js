import { StrictMode } from 'react'
import { render } from 'react-dom'

import AcceptTerms from './components/AcceptTerms'


document.addEventListener('DOMContentLoaded', function () {
    const registerForm = document.getElementById('register-form');

    if (registerForm) {

        const termsLabelEl = document.querySelector('label[for="terms"]');

        if(termsLabelEl){
            render(<StrictMode><AcceptTerms/></StrictMode>, termsLabelEl);

        }

        const showErrors = (errors) => {
            for (const field in errors) {
                const eachFieldErrors = errors[field];
                if (field === 'general') {
                    showGeneralErrors(eachFieldErrors);
                    continue;
                }
                const fieldElement = document.querySelector(`input[name='${field}']`);
                const fieldErrorsElem = document.getElementById(`${field}-errors`);
                fieldErrorsElem.innerHTML = '';
                if (!fieldErrorsElem) {
                    continue;
                }

                if (!fieldElement.classList.contains('error')) {
                    fieldElement.classList.add('error');
                }

                for (const errorMessage of eachFieldErrors) {
                    const errorMessageElement = document.createElement('span');
                    errorMessageElement.textContent = errorMessage;
                    fieldErrorsElem.appendChild(errorMessageElement);
                }
            }
        }

        const showSuccessMsg = (msg) => {
            const statusElem = document.getElementById('status');
            if (!statusElem) {
                return;
            }
            if (statusElem.classList.contains('error')) {
                statusElem.classList.remove('error');
            }
            statusElem.classList.add('success');
            statusElem.innerHTML = msg;
        }

        const showGeneralErrors = (errors) => {
            const statusElem = document.getElementById('status');
            if (!statusElem) {
                return;
            }
            if (!statusElem.classList.contains('error')) {
                statusElem.classList.add('error');
            }
            statusElem.innerHTML = '';

            for (const errorMessage of errors) {
                const errorMessageElement = document.createElement('span');
                errorMessageElement.textContent = errorMessage;
                statusElem.appendChild(errorMessageElement);
            }
        }

        registerForm.querySelectorAll('input').forEach((input) => {
            input.addEventListener('change', (e) => {
                if (e.target.classList.contains('error')) {
                    e.target.classList.remove('error')
                }

                const parentElem = e.target.parentElement;
                const errorElem = parentElem.querySelector('.errors');
                if (errorElem) {
                    errorElem.innerHTML = '';
                }

            })
        })

        registerForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const formData = new FormData(registerForm);

            const apiEndpoint = window.concordamos.rest_url + '/register';

            const btnSubmit = registerForm.querySelector('button.register-submit');
            const btnSubmitDefaultText = btnSubmit.innerHTML;
            btnSubmit.innerHTML = btnSubmit.getAttribute('data-loading-text');

            fetch(apiEndpoint, {
                method: 'POST',
                headers: {
                    'X-WP-Nonce': window.concordamos.nonce,
                },
                body: formData
            })
                .then(response => {
                    return response.json().then(data => {
                        if (response.ok) {
                            showSuccessMsg(data.message);
                            setTimeout(() => {
                                window.location.href = window.concordamos.my_account_url;
                            }, 1000);
                        } else {
                            showErrors(data);
                        }
                        btnSubmit.innerHTML = btnSubmitDefaultText;
                    });
                })
                .catch(error => {
                    console.error('An error occurred:', error);
                });
        });

        const buttonShowPassword = document.querySelector('button.show-password');
        const inputPassword = document.querySelector('[name="password"]');
        const inputRepeatPassword = document.querySelector('[name="repeat-password"]');
        buttonShowPassword.addEventListener('click', (e) => {
            if (inputPassword.getAttribute('type') === 'password') {
                inputPassword.setAttribute('type', 'text')
                inputRepeatPassword.setAttribute('type', 'text');
            } else {
                inputPassword.setAttribute('type', 'password')
                inputRepeatPassword.setAttribute('type', 'password');
            }
        })
    }
});
