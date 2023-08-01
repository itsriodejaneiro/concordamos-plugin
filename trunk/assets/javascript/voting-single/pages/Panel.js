import { __ } from '@wordpress/i18n'

const Panel = () => {
	return (
		<>
			<div className="content panel">
				<h2>{__('User panel', 'concordamos')}</h2>
			</div>
		</>
	)
}

export default Panel
