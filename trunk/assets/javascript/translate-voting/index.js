import { StrictMode } from 'react'
import { render } from 'react-dom'

import { App } from './App'

const elementRender = document.getElementById('concordamos-translate-voting-form')
const votingTemplate = JSON.parse(elementRender.dataset.template)

render(<StrictMode><App template={votingTemplate}/></StrictMode>, elementRender)
