# proofofexistence

## What is it?
Demo Live [here] (http://proofofexistence.org).

This is a tool to generate a Proof-of-Existence of a file or record on Private Blockchain. By submiting this form you will be uploading the file's signature with it's associated information into the Private Decentralized Public Ledger. Records stored here can later be fetched to prove that this file existed on particular date & time with the associated information.

Computationally & Technologically it is impossible to fake the Document's signature and/or modify the past records. Hence this PoE can be used to prove the file's existence in legal matters.

## How it works?
* Step 1: Fill the form & Upload the file
* Step 2: Copy the link & Share it with people you want to prove its existence
* Step 3: Anyone with link or file can verify the existence of the file at particular date & time.

We have extended the existing MultiChain PHP Library [libphp-multichain] (https://github.com/Kunstmaan/libphp-multichain) by adding this below method to the poe/poe-api/vendor/kunstmaan/libphp-multichain/src/be/kunstmaan/multichain/MultichainClient.php file.

	public function executeApi($method, $paramArray) {
        	return $this->jsonRPCClient->execute($method, $paramArray);
	}

## License 
Source code available under [Apache License 2.0 (Apache-2.0)] (https://tldrlegal.com/license/apache-license-2.0-(apache-2.0))  
    
