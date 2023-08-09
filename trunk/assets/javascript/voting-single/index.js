import { StrictMode } from 'react'
import { render } from 'react-dom'

import { App } from './App'

const elementRender = document.getElementById('concordamos-voting-single')
const initialData = {
	credits_voter: elementRender.dataset.credits_voter,
	date_end: elementRender.dataset.date_end,
	options: elementRender.dataset.options,
	is_panel: elementRender.dataset.is_panel,
}

render(<StrictMode><App initialData={initialData}/></StrictMode>, elementRender)
