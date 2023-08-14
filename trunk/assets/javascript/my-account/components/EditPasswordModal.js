import { __ } from '@wordpress/i18n'
import { useState } from 'react'

import Modal from '../../shared/components/Modal'
import { apiFetch } from '../../shared/hooks/fetch'

export default function EditPasswordModal ({ controller }) {
	const [password, setPassword] = useState('')
	const [confirmPassword, setConfirmPassword] = useState('')
	const matchingPassword = password === confirmPassword

	function onPasswordChange (event) {
		setPassword(event.target.value)
	}

	function onConfirmChange (event) {
		setConfirmPassword(event.target.value)
	}

	function handleSubmit (event) {
		event.preventDefault()
		if (confirmPassword && matchingPassword) {
			apiFetch('PATCH', 'my-account', {
				'user_id': concordamos.user_id,
				'password': password,
			})
			.then(response => {
				if (response.status === 'error') {
					throw new Error(response.message)
				} else {
					controller.close()
				}
			})
		}
	}

	function handleCancel (event) {
		setPassword('')
		setConfirmPassword('')
		controller.close()
	}

	return (
		<Modal controller={controller}>
			<h2>{__('Edit password', 'concordamos')}</h2>
			<div className="field field-text">
				<label>
					<span>{__('Password', 'concordamos')}</span>
					<input type="password" placeholder={__('Insert your password here', 'concordamos')} value={password} onChange={onPasswordChange}/>
				</label>
			</div>
			<div className="field field-text">
				<label>
					<span>{__('Repeat password', 'concordamos')}</span>
					<input type="password" placeholder={__('Repeat your password here', 'concordamos')} value={confirmPassword} onChange={onConfirmChange}/>
				</label>
			</div>
			{(confirmPassword && !matchingPassword) ? (
				<p className="warning-message">{__("Passwords don't match", 'concordamos')}</p>
			) : null}
			<div className="buttons">
				<button type="button" className="button primary" onClick={handleSubmit} disabled={confirmPassword && !matchingPassword}>{__('Save', 'concordamos')}</button>
				<button type="button" className="button link" onClick={handleCancel}>{__('Cancel', 'concordamos')}</button>
			</div>
		</Modal>
	)
}
