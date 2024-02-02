import { __ } from '@wordpress/i18n'

import Modal from '../../shared/components/Modal'

export default function TermsOfUseModal ({ controller }) {

    return (
		<Modal controller={controller}>
			<h2>{__('Terms of Use', 'concordamos')}</h2>
			<div className="terms-of-use-text" dangerouslySetInnerHTML={{__html: concordamos.terms_of_use}}/>
		</Modal>
	)
}
