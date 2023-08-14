import { StrictMode } from 'react'
import { render } from 'react-dom'

import { App } from './App'
import { VotingAdmin } from './VotingAdmin'

const mainAppRender = document.getElementById('concordamos-voting-single')
const adminAppRender = document.getElementById('concordamos-voting-admin')

const initialData = {
	credits_voter: mainAppRender.dataset.credits_voter,
	date_end: mainAppRender.dataset.date_end,
	date_start: mainAppRender.dataset.date_start,
	options: mainAppRender.dataset.options,
	is_panel: mainAppRender.dataset.is_panel,
}

render(<StrictMode><App initialData={initialData}/></StrictMode>, mainAppRender)
if (adminAppRender) {
	render(<StrictMode><VotingAdmin initialData={initialData}/></StrictMode>, adminAppRender)
}
