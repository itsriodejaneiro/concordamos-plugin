const noop = () => {}

export default function ResizableTextarea ({ onInput = noop, ...attrs }) {
	function innerOnInput (event) {
		const textarea = event.target
		textarea.style.height = 'auto'
		textarea.style.height = `${textarea.scrollHeight + 6}px`
		onInput(event)
	}

	return (
		<textarea className="resizable-textarea" onInput={innerOnInput} {...attrs}/>
	)
}
