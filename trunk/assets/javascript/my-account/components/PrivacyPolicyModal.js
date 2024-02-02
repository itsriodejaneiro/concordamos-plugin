import { __ } from '@wordpress/i18n'
import Modal from '../../shared/components/Modal'

export default function PrivacyPolicyModal ({ controller }) {

    return (
		<Modal className="privacy-modal" controller={controller}>
			<h2>{__('Privacy Policy', 'concordamos')}</h2>
			<div className="privacy-policy-text" dangerouslySetInnerHTML={{__html: concordamos.privacy_policy}}/>
		</Modal>
	)
}
