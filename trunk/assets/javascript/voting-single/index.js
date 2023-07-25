import React from 'react'
import ReactDOM from 'react-dom'

import { App } from './App'

const elementRender = document.getElementById('concordamos-voting-single')
const initialData = {
	credits_voter: elementRender.dataset.credits_voter,
	date_end: elementRender.dataset.date_end,
	options: elementRender.dataset.options
}

ReactDOM.render(<App initialData={initialData} />, elementRender);
