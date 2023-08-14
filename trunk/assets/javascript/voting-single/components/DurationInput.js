import { __ } from '@wordpress/i18n'
import DatePicker from 'react-datepicker'

import 'react-datepicker/dist/react-datepicker.css'

export default function DurationInput ({ format, maxValue, minValue, value, onChange, }) {
	return (
		<DatePicker
			dateFormat={format ?? __('MM/dd/yyyy hh:mm aa', 'concordamos')}
			minTime={minValue}
			maxTime={maxValue}
			selected={value}
			onChange={onChange}
			showTimeSelect={true}
			timeIntervals={10}
		/>
	)
}
