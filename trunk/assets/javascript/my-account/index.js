import { StrictMode } from 'react'
import { render } from 'react-dom'

import { App } from './App'

const elementRender = document.getElementById('concordamos-my-account')

render(<StrictMode><App/></StrictMode>, elementRender)
