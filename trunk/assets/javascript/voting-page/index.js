import { StrictMode} from 'react'
import { render } from 'react-dom'

import { App } from './App'

const elementRender = document.getElementById('concordamos-voting-form')

render(<StrictMode><App/></StrictMode>, elementRender)
