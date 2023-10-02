import { __ } from '@wordpress/i18n'
import { useState } from 'react'

import Modal from '../../shared/components/Modal'

export default function PrivacyPolicyModal ({ controller, onClick, value }) {
    const [textPrivacyPolicy, setTextPrivacyPolicy] = useState('privacy policy text');

    return (
		<Modal controller={controller}>
			<h2>{__('Privacy Policy', 'concordamos')}</h2>
			<div className="privacy-policy-text">
				<p>
                    {textPrivacyPolicy}   
                </p>
			</div>
		</Modal>
	)
}
