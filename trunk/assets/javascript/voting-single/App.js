import Single from "./pages/Single";
import Panel from "./pages/Panel";

export function App({ initialData }) {
	const { credits_voter, date_end, is_panel, options } = initialData;
	return (
		<>
			{
				is_panel
				? <Panel initialData={initialData} />
				: <Single initialData={initialData} />
			}
		</>
	)
}
