import { __ } from '@wordpress/i18n'

import EditNameModal from './EditNameModal'
import { useModal } from '../../shared/components/Modal'

export default function UserSettings ({ user }) {
	const editNameModal = useModal(true)
	const editEmailModal = useModal(false)

	return (
		<>
			<div className="my-account-settings">
				<div className="my-account-box">
					<h2>{__('My data', 'concordamos')}</h2>
					<div className="my-account-field">
						<dl>
							<dt>{__('Name', 'concordamos')}</dt>
							<dd>{user.name}</dd>
						</dl>
						<button type="button" onClick={editNameModal.open}>{__('Edit name', 'concordamos')}</button>
					</div>
					<div className="my-account-field">
						<dl>
							<dt>{__('My e-mail', 'concordamos')}</dt>
							<dd>{user.email}</dd>
						</dl>
						<button type="button" onClick={editEmailModal.open}>{__('Edit e-mail', 'concordamos')}</button>
					</div>
				</div>
				<div className="my-account-box">
					<h2>{__('General settings', 'concordamos')}</h2>
					<button type="button">{__('Edit password', 'concordamos')}</button>
					<button type="button">{__('Review our privacy policy', 'concordamos')}</button>
					<button type="button">{__('Review our terms of use', 'concordamos')}</button>
					<button type="button delete">{__('Delete account', 'concordamos')}</button>
				</div>
			</div>
			<EditNameModal controller={editNameModal} value={user.name} onChange={(event) => user.name = event}/>
		</>
	)
}
