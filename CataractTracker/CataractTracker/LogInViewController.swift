//
//  LogInViewController.swift
//  CataractTracker
//
//  Created by Junyuan Suo on 11/4/16.
//  Copyright © 2016 JYLock. All rights reserved.
//

import UIKit

class LogInViewController: UIViewController {
    
    
    
    // MARK: - Properties
    @IBAction func signinButton(sender: UIButton) {
        if let username = userIDTextField.text, let password = passcodeTextField.text {
            login_request(username.stringByTrimmingCharactersInSet(NSCharacterSet.whitespaceAndNewlineCharacterSet()), password: password.stringByTrimmingCharactersInSet(NSCharacterSet.whitespaceAndNewlineCharacterSet()))
        }
        
    }
    
    @IBOutlet weak var signInBtn: FancyBtn!
    @IBOutlet weak var signUpBtn: FancyBtn!
    @IBOutlet weak var userIDTextField: FancyField!
    @IBOutlet weak var passcodeTextField: FancyField!
    
    struct Constant {
        static let segueToApp = "login to app"
    }
    
    // MARK: - Navigation
    override func prepareForSegue(segue: UIStoryboardSegue, sender: AnyObject?) {
        if segue.identifier == Constant.segueToApp {
            print("to app")
        }
    }
    
    // MARK: - Helper functions
    
    func login_request(username: String, password: String) {
        let url:NSURL = NSURL(string: SharingManager.Constant.loginURL)!
        let session = NSURLSession.sharedSession()
        
        let request = NSMutableURLRequest(URL: url)
        request.HTTPMethod = "POST"
        request.cachePolicy = NSURLRequestCachePolicy.ReloadIgnoringCacheData
        
        let paramString = "username=\(username)&password=\(password)"
        request.HTTPBody = paramString.dataUsingEncoding(NSUTF8StringEncoding)
        
        let task = session.dataTaskWithRequest(request) {
            (let data, let response, let error) in
            
            guard let _:NSData = data, let _:NSURLResponse = response  where error == nil else {
                print("error: \(error)")
                return
            }
            
            // let dataString = NSString(data: data!, encoding: NSUTF8StringEncoding)
            
            do {
                let json = try NSJSONSerialization.JSONObjectWithData(data!, options: .AllowFragments) as! NSDictionary
                
                // login failed
                if let loginError = json["error"] as? String {
                    print(loginError)
                    return
                }
                // login successfully, save user information
                if let username = json["username"] as? String {
                    UserInfo.username = username
                }
                dispatch_async(dispatch_get_main_queue(), {
                    self.performSegueWithIdentifier(Constant.segueToApp, sender: self)
                })
            } catch {
                print("error serializing JSON: \(error)")
            }
            
        }
        
        task.resume()
    }


}

