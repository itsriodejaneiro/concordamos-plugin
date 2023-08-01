import { __, _x } from '@wordpress/i18n'
import { useState, useEffect } from 'react'
import DatePicker from 'react-datepicker'

import 'react-datepicker/dist/react-datepicker.css'

const StartEnd = ({ description, label, setStartEndDateTime }) => {

	const today = new Date()

	const [startDate, setStartDate] = useState(today.getTime())
	const [endDate, setEndDate] = useState(today.getTime() + 24*60*60*1000) // increment 24 hours

	useEffect(() => {
		setStartEndDateTime([startDate, endDate])
	}, [startDate, endDate])

	return (
		<>
			<div className="field field-start-end">
				<span className="title-section">{label}</span>
				{
					description
					? <span className="description-section">{description}</span>
					: null
				}

				<div className="line">
					<span className="label">{__('Start', 'concordamos')}</span>

					<div className="date-time-inputs">
						<DatePicker
							className="date"
							dateFormat={__('MM/dd/yyyy', 'concordamos')}
							minDate={new Date()}
							selected={startDate}
							onChange={(date) => setStartDate(date.getTime())}
						/>

						<span class="at">{_x('at', 'hour', 'concordamos')}</span>

						<DatePicker
							className="time"
							minDate={today}
							selected={startDate}
							onChange={(date) => setStartDate(date.getTime())}
							showTimeSelect
							showTimeSelectOnly
							timeCaption={_x('Time', 'hour', 'concordamos')}
							dateFormat={__('hh:mm aa', 'concordamos')}
							timeIntervals={10}
						/>
					</div>
				</div>
				<div className="line">
					<span className="label">{__('End', 'concordamos')}</span>

					<div className="date-time-inputs">
						<DatePicker
							className="date"
							dateFormat={__('MM/dd/yyyy', 'concordamos')}
							minDate={today}
							selected={endDate}
							onChange={(date) => setEndDate(date.getTime())}
						/>

						<span class="at">{_x('at', 'hour', 'concordamos')}</span>

						<DatePicker
							className="time"
							minDate={today}
							selected={endDate}
							onChange={(date) => setEndDate(date.getTime())}
							showTimeSelect
							showTimeSelectOnly
							timeCaption={_x('Time', 'hour', 'concordamos')}
							dateFormat={__('hh:mm aa', 'concordamos')}
							timeIntervals={10}
						/>
					</div>
				</div>
			</div>
		</>
	)
}

export default StartEnd
