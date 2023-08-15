import { __, _x } from '@wordpress/i18n'
import classNames from 'classnames'

import CopyToClipboard from './CopyToClipboard'
import Image from '../../voting-archive/components/Image'

export default function VotingLink ({ href, withButton = false }) {
	return (
		<div className={classNames('voting-link', { 'voting-link--with-button': withButton })}>
			<CopyToClipboard text={href}>
				<Image src="copy.svg" alt={__('Copy to clipboard', 'concordamos')} title={__('Copy to clipboard', 'concordamos')}/>
			</CopyToClipboard>
			<a href={href} target="_blank">{href}</a>
			{withButton ? (
				<CopyToClipboard className="copy-button" text={href}>
					{_x('Copy', 'verb', 'concordamos')}
				</CopyToClipboard>
			) : null}
		</div>
	)
}
