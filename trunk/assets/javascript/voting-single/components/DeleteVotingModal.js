import { __, sprintf } from '@wordpress/i18n'

import Modal from '../../shared/components/Modal'

export default function DeleteVotingModal ({ controller }) {
	return (
		<Modal controller={controller} danger={true}>
			<h2>{__('Do you want to delete voting?', 'concordamos')}</h2>
			<p dangerouslySetInnerHTML={ { __html: sprintf(__('For deleting this voting, send a message for the platform administration with the subject "Delete voting" at the email <a href="mailto:%s">%s</a>'), concordamos.admin_email, concordamos.admin_email) } }/>
			<div className="buttons">
				<button type="button" className="button primary" onClick={controller.close}>{__('Go back', 'concordamos')}</button>
			</div>
		</Modal>
	)
}
