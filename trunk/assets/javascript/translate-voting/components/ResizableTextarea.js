import { useEffect, useRef } from 'react'

const noop = () => {}

function resizeTextarea (el) {
	el.style.height = 'auto'
	el.style.height = `${el.scrollHeight + 6}px`
}

export default function ResizableTextarea ({ onInput = noop, ...attrs }) {
	const textareaRef = useRef(null)

	useEffect(() => {
		if (textareaRef.current) {
			resizeTextarea(textareaRef.current)
		}
	}, [textareaRef.current])

	function innerOnInput (event) {
		resizeTextarea(event.target)
		onInput(event)
	}

	return (
		<textarea className="resizable-textarea" ref={textareaRef} onInput={innerOnInput} {...attrs}/>
	)
}
