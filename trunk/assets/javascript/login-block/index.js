import { StrictMode } from 'react'
import { render } from 'react-dom'

const elementsRender = [...document.getElementsByClassName('concordamos-login-block')]

elementsRender.forEach((elementRender) => {
	render(<StrictMode></StrictMode>, elementRender)
})
