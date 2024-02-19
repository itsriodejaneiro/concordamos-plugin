import { StrictMode } from 'react'
import { render } from 'react-dom'

import { App } from './App'
import { VotingAdmin } from './VotingAdmin'
import { getLocale } from '../shared/utils/i18n'

const mainAppRender = document.getElementById('concordamos-voting-single')
const adminAppRender = document.getElementById('concordamos-voting-admin')

const initialData = {
	credits_voter: mainAppRender.dataset.credits_voter,
	date_end: mainAppRender.dataset.date_end,
	date_start: mainAppRender.dataset.date_start,
	is_panel: mainAppRender.dataset.is_panel,
	logged: mainAppRender.dataset.logged,
	options: mainAppRender.dataset.options,
	results_end: mainAppRender.dataset.results_end,
	voting_closed: mainAppRender.dataset.voting_closed
}

document.querySelectorAll('time[data-format]').forEach((el) => {
	const date = new Date(el.dateTime)
	const format = JSON.parse(el.dataset.format)
	const formatter = new Intl.DateTimeFormat(getLocale(), format)
	el.textContent = formatter.format(date)
})

render(<StrictMode><App initialData={initialData}/></StrictMode>, mainAppRender)
if (adminAppRender) {
	render(<StrictMode><VotingAdmin initialData={initialData}/></StrictMode>, adminAppRender)
}
