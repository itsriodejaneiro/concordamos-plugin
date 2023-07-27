import { useState, useEffect } from "react"
import DatePicker from "react-datepicker";

import "react-datepicker/dist/react-datepicker.css";

const StartEnd = ({ description, label, setStartEndDateTime }) => {

	const today = new Date()

	const [startDate, setStartDate] = useState(today.getTime())
	const [endDate, setEndDate] = useState(today.getTime() + 24*60*60*1000); // increment 24 hours

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
					<span className="label">Start</span>

					<div className="date-time-inputs">
						<DatePicker
							className="date"
							dateFormat="dd/MM/yyyy"
							minDate={new Date()}
							selected={startDate}
							onChange={(date) => setStartDate(date.getTime())}
						/>

						<span class="at">at</span>

						<DatePicker
							className="time"
							minDate={today}
							selected={startDate}
							onChange={(date) => setStartDate(date.getTime())}
							showTimeSelect
							showTimeSelectOnly
							timeCaption="Horário"
							dateFormat="hh:mm aa"
							timeIntervals={10}
						/>
					</div>
				</div>
				<div className="line">
					<span className="label">End</span>

					<div className="date-time-inputs">
						<DatePicker
							className="date"
							dateFormat="dd/MM/yyyy"
							minDate={today}
							selected={endDate}
							onChange={(date) => setEndDate(date.getTime())}
						/>

						<span class="at">at</span>

						<DatePicker
							className="time"
							minDate={today}
							selected={endDate}
							onChange={(date) => setEndDate(date.getTime())}
							showTimeSelect
							showTimeSelectOnly
							timeCaption="Time"
							dateFormat="hh:mm aa"
							timeIntervals={10}
						/>
					</div>
				</div>
			</div>
		</>
	)
}

export default StartEnd
