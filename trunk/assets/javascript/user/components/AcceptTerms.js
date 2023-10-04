import { __ } from "@wordpress/i18n";
import PrivacyPolicyModal from "../../my-account/components/PrivacyPolicyModal";
import TermsOfUseModal from "../../my-account/components/TermsOfUseModal";
import { useModal } from "../../shared/components/Modal";

export default function AcceptTerms(){

    const privacyPolicyModal = useModal(false);
    const termsOfUseModal = useModal(false);
    return(
        <>
            <span>
                { __( 'You accept the', 'concordamos' )} 
                <a href="javascript:void(0)" onClick={privacyPolicyModal.open}> 
                    { __( 'Privacy Policy', 'concordamos' )}
                </a>
                { __( 'and', 'concordamos' )} 
                <a href="javascript:void(0)" onClick={termsOfUseModal.open}>
                    { __( 'Terms of Use', 'concordamos' )}
                </a>
            </span>
            <PrivacyPolicyModal controller={privacyPolicyModal}/>
			<TermsOfUseModal controller={termsOfUseModal}/>
        </> 
    )
}
