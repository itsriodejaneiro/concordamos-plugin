import { __ } from '@wordpress/i18n'

import DeleteVotingModal from './components/DeleteVotingModal'
import EditVotingModal from './components/EditVotingModal'
import Image from '../voting-archive/components/Image'
import { useModal } from '../shared/components/Modal'

export function VotingAdmin ({ initialData }) {
	const deleteVotingModal = useModal(false)
	const editVotingModal = useModal(false)

	console.log(initialData)

	return (
		<>
			<button type="button link" onClick={editVotingModal.open}><Image src="calendar2.svg"/>{__('Change duration', 'concordamos')}</button>
			<button type="button link" onClick={deleteVotingModal.open}><Image src="delete-outline.svg"/>{__('Delete voting', 'concordamos')}</button>
			<DeleteVotingModal controller={deleteVotingModal}/>
			<EditVotingModal controller={editVotingModal} initialData={initialData}/>
		</>
	)
}
