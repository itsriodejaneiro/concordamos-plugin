import { __ } from '@wordpress/i18n'
import { useState } from 'react'

import Modal from '../../shared/components/Modal'

export default function TermsOfUseModal ({ controller, onClick, value }) {
    const [textTermsOfUse, setTextTermsOfUse] = useState('terms of use text');

    return (
		<Modal controller={controller}>
			<h2>{__('Terms of Use', 'concordamos')}</h2>
			<div className="terms-of-use-text">
				<p>
                    {textTermsOfUse}   
                </p>
			</div>
		</Modal>
	)
}
