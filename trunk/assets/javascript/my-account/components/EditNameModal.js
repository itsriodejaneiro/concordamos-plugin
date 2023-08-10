import { __ } from '@wordpress/i18n'
import { useState } from 'react'

import Modal from '../../shared/components/Modal'

export default function EditNameModal ({ controller, onChange, value }) {
	const [innerValue, setInnerValue] = useState(value)

	function innerOnChange (event) {
		setInnerValue(event.target.value)
	}

	function handleSubmit (event) {
		event.preventDefault()
		fetch(new URL('my-account', concordamos.rest_url), {
			method: 'PATCH',
			headers: {
				'Content-Type': 'application/json',
				'X-WP-Nonce': concordamos.nonce
			},
			body: JSON.stringify({
				'user_id': concordamos.user_id,
				'name': innerValue,
			}),
		})
		.then(response => response.json())
		.then(response => {
			if (response.status === 'error') {
				throw new Error(response.message)
			} else {
				onChange(innerValue)
				controller.close()
			}
		})
		.catch(error => console.error(error))
	}

	function handleCancel (event) {
		setInnerValue(value)
		controller.close()
	}

	return (
		<Modal controller={controller}>
			<h2>{__('Edit name', 'concordamos')}</h2>
			<div className="field field-text">
				<label>
					<span>{__('Name', 'concordamos')}</span>
					<input type="text" placeholder={__('Name Surname', 'concordamos')} value={innerValue} onChange={innerOnChange}/>
				</label>
			</div>
			<div className="buttons">
				<button type="button" className="button primary" onClick={handleSubmit}>{__('Save', 'concordamos')}</button>
				<button type="button" className="button link" onClick={handleCancel}>{__('Cancel', 'concordamos')}</button>
			</div>
		</Modal>
	)
}
