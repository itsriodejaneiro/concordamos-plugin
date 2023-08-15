import { __ } from '@wordpress/i18n'
import { useMemo } from 'react'

import VotingLink from './VotingLink'
import { createFile } from '../utils/file'
import { getPanelUrl } from '../../shared/utils/location'
import { useFetch } from '../../shared/hooks/fetch'

export default function VotingLinks ({ initialData }) {
	const { data } = useFetch(`voting-links/?v_id=${concordamos.v_id}`)

	const individualUrls = useMemo(() => {
		if (!data) {
			return []
		}
		return data.uids.map((uid) => new URL(uid, data.permalink).toString())
	}, [data])

	const downloadUrl = useMemo(() => {
		return createFile(individualUrls.join('\r\n'), 'text/plain')
	}, [individualUrls])

	return data ? (
		<div className="wrapper">
			<div className="content">
				<div className="voting-links-grid">
					<div className="voting-links-column">
						<div className="voting-links-card">
							<h2>{__('Voting URL', 'concordamos')}</h2>
							<p>{__('Detailed results URL', 'concordamos')}</p>
							<VotingLink href={data.permalink} withButton/>
						</div>
						<div className="voting-links-card">
							<h2>{__('Private administrator URL', 'concordamos')}</h2>
							<p>{__('Save this URL for managing the event:', 'concordamos')}</p>
							<VotingLink href={getPanelUrl(data.permalink)} withButton/>
						</div>
					</div>
					{ data.status === 'private' ? (
						<div className="voting-links-column">
							<h2>{__('Individual vote URLs', 'concordamos')}</h2>
							<p>{__('To be shared privately with voters', 'concordamos')}</p>
							{individualUrls.map((url) => (
								<VotingLink href={url}/>
							))}
							<a href={downloadUrl} download={`links_${data.slug}.txt`}>{__('Download as text file (.TXT)', 'concordamos')}</a>
						</div>
					) : null }
				</div>
			</div>
		</div>
	) : null
}
