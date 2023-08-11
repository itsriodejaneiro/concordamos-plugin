import { StrictMode } from 'react'
import { render } from 'react-dom'

import { App } from './App'

const elementsRender = [...document.getElementsByClassName('concordamos-votings-block')]

elementsRender.forEach((elementRender) => {
	render(<StrictMode><App/></StrictMode>, elementRender)
})
