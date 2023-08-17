import { __, sprintf } from '@wordpress/i18n'

import Modal from '../../shared/components/Modal'
import { apiFetch } from '../../shared/hooks/fetch'
import { navigateTo } from '../../shared/utils/location'

export default function DeleteAccountModal ({ controller }) {
	function handleSubmit (e) {
		apiFetch('DELETE', 'my-account', {
			'user_id': concordamos.user_id,
		}).then((response) => {
			if (response.status !== 'error') {
				navigateTo(concordamos.login_url)
			}
		})
	}

	return (
		<Modal controller={controller} danger={true}>
			<h2>{__('Delete account?', 'concordamos')}</h2>
			<p dangerouslySetInnerHTML={ { __html: __('The votings you created will remain active. You can delete them before that. Your votes will remain computed, but anonymously', 'concordamos') } }/>
			<div className="buttons">
				<button className="button danger" onClick={handleSubmit}>{__('Delete my account', 'concordamos')}</button>
				<button className="button link" onClick={controller.close}>{__('Cancel', 'concordamos')}</button>
			</div>
		</Modal>
	)
}
