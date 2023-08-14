import { __ } from '@wordpress/i18n'
import { useState } from 'react'

import DurationInput from './DurationInput'
import Modal from '../../shared/components/Modal'
import { apiFetch } from '../../shared/hooks/fetch'
import { reloadPage } from '../../shared/utils/location'

function minDate (date1, date2) {
	if (date1.getTime() <= date2.getTime()) {
		return date1
	} else {
		return date2
	}
}

export default function EditVotingModal({ controller, initialData }) {
	const maxDate = new Date(Number.MAX_SAFE_INTEGER)

	const initialStart = new Date(Number(initialData.date_start))
	const initialEnd = new Date(Number(initialData.date_end))

	const [startDate, setStartDate] = useState(initialStart)
	const [endDate, setEndDate] = useState(initialEnd)

	function patchDates (start, end) {
		apiFetch('PATCH', 'voting', {
			date_start: start.getTime(),
			date_end: end.getTime(),
			user_id: concordamos.user_id,
			v_id: concordamos.v_id,
		})
		.then((response) => {
			controller.close()
			reloadPage()
		})
	}

	function changeDates (event) {
		patchDates(startDate, endDate)
	}

	function finishVoting (event) {
		patchDates(initialStart, Math.min(initialEnd, new Date()))
	}

	return (
		<Modal controller={controller}>
			<h2>{__('Change voting duration', 'concordamos')}</h2>
			<label>
				<span>{__('Start', 'concordamos')}</span>
				<DurationInput
					minValue={minDate(initialStart, new Date())}
					maxValue={endDate}
					value={startDate}
					onChange={setStartDate}
				/>
			</label>
			<label>
				<span>{__('End', 'concordamos')}</span>
				<DurationInput
					minValue={startDate}
					maxValue={maxDate}
					value={endDate}
					onChange={setEndDate}
				/>
			</label>
			<div className="buttons">
				<button type="button" className="button primary" onClick={changeDates}>{__('Done', 'concordamos')}</button>
				<button type="button" className="button link" onClick={finishVoting}>{__('End voting', 'concordamos')}</button>
			</div>
		</Modal>
	)
}
