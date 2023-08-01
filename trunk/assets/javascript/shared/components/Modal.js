import { __ } from '@wordpress/i18n'
import { useEffect, useRef } from 'react'

export default function Modal ({ children, onClose, open }) {
	const dialogRef = useRef()

	useEffect(() => {
		if (open) {
			dialogRef.current.focus()
		}
	}, [open])

	return (
		<div className={`voting-modal__wrapper ${open ? 'open' : ''}`}>
			<dialog className="voting-modal" ref={dialogRef} open={open}>
				<header>
					<button type="button" className="button close" onClick={onClose}>{__('Close', 'concordamos')}</button>
				</header>
				<main>{children}</main>
			</dialog>
		</div>
	)
}
