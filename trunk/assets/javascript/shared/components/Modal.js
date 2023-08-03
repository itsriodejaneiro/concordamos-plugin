import { __ } from '@wordpress/i18n'
import classNames from 'classnames'
import { useEffect, useRef, useState } from 'react'

export function useModal (startOpen = false) {
	const [isOpen, setIsOpen] = useState(startOpen)

	const open = () => setIsOpen(true)
	const close = () => setIsOpen(false)
	const toggle = () => setIsOpen((isOpen) => !isOpen)

	return { close, isOpen, open, toggle }
}

export default function Modal ({ children, controller }) {
	const { close, isOpen } = controller

	const dialogRef = useRef()

	useEffect(() => {
		if (isOpen) {
			dialogRef.current.focus()
		}
	}, [isOpen])

	return (
		<div className={classNames('voting-modal__wrapper', { 'open': isOpen })}>
			<dialog className="voting-modal" ref={dialogRef} open={isOpen}>
				<header>
					<button type="button" className="button close" onClick={close}>{__('Close', 'concordamos')}</button>
				</header>
				<main>{children}</main>
			</dialog>
		</div>
	)
}
