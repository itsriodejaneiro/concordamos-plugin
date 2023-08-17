import { __ } from '@wordpress/i18n'

import DeleteAccountModal from './DeleteAccountModal'
import EditEmailModal from './EditEmailModal'
import EditNameModal from './EditNameModal'
import EditPasswordModal from './EditPasswordModal'
import { useModal } from '../../shared/components/Modal'

export default function UserSettings ({ user }) {
	const deleteAccountModal = useModal(false)
	const editEmailModal = useModal(false)
	const editNameModal = useModal(false)
	const editPasswordModal = useModal(false)

	return (
		<>
			<div className="my-account-settings">
				<div className="my-account-box">
					<div className="container">
						<h2>{__('My data', 'concordamos')}</h2>
						<div className="my-account-field">
							<dl>
								<dt>{__('Name', 'concordamos')}</dt>
								<dd>{user.name}</dd>
							</dl>
							<button className="edit" type="button" onClick={editNameModal.open}>{__('Edit name', 'concordamos')}</button>
						</div>
						<div className="my-account-field">
							<dl>
								<dt>{__('My email', 'concordamos')}</dt>
								<dd>{user.email}</dd>
							</dl>
							<button className="edit" type="button" onClick={editEmailModal.open}>{__('Edit email', 'concordamos')}</button>
						</div>
					</div>
				</div>
				<div className="my-account-box">
					<div className="container">
						<h2>{__('General settings', 'concordamos')}</h2>
						<button className="edit" type="button" onClick={editPasswordModal.open}>{__('Edit password', 'concordamos')}</button>
						<button className="edit" type="button">{__('Review our privacy policy', 'concordamos')}</button>
						<button className="edit" type="button">{__('Review our terms of use', 'concordamos')}</button>
						<button className="delete-acount" type="button delete" onClick={deleteAccountModal.open}>{__('Delete account', 'concordamos')}</button>
					</div>
				</div>
			</div>
			<DeleteAccountModal controller={deleteAccountModal}/>
			<EditNameModal controller={editNameModal} value={user.name} onChange={(event) => user.name = event}/>
			<EditEmailModal controller={editEmailModal} value={user.email} onChange={(event) => user.email = event}/>
			<EditPasswordModal controller={editPasswordModal}/>
		</>
	)
}
