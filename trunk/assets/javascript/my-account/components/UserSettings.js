import { __ } from '@wordpress/i18n'

import EditEmailModal from './EditEmailModal'
import EditNameModal from './EditNameModal'
import EditPasswordModal from './EditPasswordModal'
import { useModal } from '../../shared/components/Modal'

export default function UserSettings ({ user }) {
	const editEmailModal = useModal(false)
	const editNameModal = useModal(false)
	const editPasswordModal = useModal(false)

	return (
		<div className="my-account-settings">
			<div className="my-account-box">
				<div className="container">
					<h2>{__('My data', 'concordamos')}</h2>
					<div className="my-account-field">
						<dl>
							<dt>{__('Name', 'concordamos')}</dt>
							<dd>{user.name}</dd>
						</dl>
						<button className="edit" type="button">{__('Edit name', 'concordamos')}</button>
					</div>
					<div className="my-account-field">
						<dl>
							<dt>{__('My e-mail', 'concordamos')}</dt>
							<dd>{user.email}</dd>
						</dl>
						<button className="edit" type="button">{__('Edit e-mail', 'concordamos')}</button>
					</div>
				</div>
			</div>
			<div className="my-account-box">
				<div className="container">
					<h2>{__('General settings', 'concordamos')}</h2>
					<button className="edit" type="button">{__('Edit password', 'concordamos')}</button>
					<button className="edit" type="button">{__('Review our privacy policy', 'concordamos')}</button>
					<button className="edit" type="button">{__('Review our terms of use', 'concordamos')}</button>
					<button className="delete-acount" type="button delete">{__('Delete account', 'concordamos')}</button>
				</div>
			</div>
		</div>
	)
}
