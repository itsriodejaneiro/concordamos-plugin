import { __ } from '@wordpress/i18n'

export default function UserSettings ({ user }) {
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
						<button type="button">{__('Edit name', 'concordamos')}</button>
					</div>
					<div className="my-account-field">
						<dl>
							<dt>{__('My e-mail', 'concordamos')}</dt>
							<dd>{user.email}</dd>
						</dl>
						<button type="button">{__('Edit e-mail', 'concordamos')}</button>
					</div>
				</div>
			</div>
			<div className="my-account-box">
				<div className="container">
					<h2>{__('General settings', 'concordamos')}</h2>
					<button type="button">{__('Edit password', 'concordamos')}</button>
					<button type="button">{__('Review our privacy policy', 'concordamos')}</button>
					<button type="button">{__('Review our terms of use', 'concordamos')}</button>
					<button type="button delete">{__('Delete account', 'concordamos')}</button>
				</div>
			</div>
		</div>
	)
}
