//
//  SharingManager.swift
//  CataractTracker
//
//  Created by Junyuan Suo on 11/4/16.
//  Copyright © 2016 JYLock. All rights reserved.
//

import Foundation
import UIKit

/*
 This class contains variables that are shared across the entire application
 */

class SharingManager {
    
    /*
     singleton instance of SharingManager, this instance is shared among the whole application
     */
    static let sharedInstance = SharingManager()
    /*
     handlers to be executed whenever moments get updated. usually the handler is a closure that
     refresh the UIView
     */
    var momentsUpdateHandlers = Array<(Void -> Void)>()
    // all moments download from the database
    var moments = [Moment]() {
        didSet {
            for handler in momentsUpdateHandlers {
                handler()
            }
        }
    }
    let defaults = NSUserDefaults.standardUserDefaults()
    let semaphore = dispatch_semaphore_create(1)
    
   
    
    struct Constant {
        static let NumberOfMomentsToFetch: UInt = 10
        static let minimumTimeInterval = 0.000001
        static let maxThumbnailSize: CGFloat = 1000
        //static let baseServerURL = "http://127.0.0.1:8000/locator-cam/"
        static let baseServerURL = "https://locatorcam.herokuapp.com/locator-cam/"
        static let loginURL = baseServerURL + "login/"
        static let searchUserURL = baseServerURL + "search-user/"
        static let uploadMomentURL = baseServerURL + "upload-moment/"
        static let fetchMomentsURL = baseServerURL + "fetch-moments/"
        static let fetchPhotoURL = baseServerURL + "fetch-photo/"
        static let logoutURL = baseServerURL + "logout/"
        
     }
    
    
    // add a handler for updating moments
    func addMomentsUpdatedHandler(handler: Void -> Void) {
        momentsUpdateHandlers.append(handler)
    }
    
    
    func fetchMoments(publishedEarlier publishedEarlier: Bool, publishedLater: Bool, spinner: UIActivityIndicatorView?, refreshControl: UIRefreshControl?) {
        // run the whole fetching process on a different queue to
        // avoid blocking the main queue
        dispatch_async(dispatch_get_global_queue(QOS_CLASS_UTILITY, 0)) {
            // use semaphore to avoid moments array being updated by multiple threads
            let timeout = dispatch_time(DISPATCH_TIME_NOW, 2 * 60 * 1000 * 1000 * 1000)
            dispatch_semaphore_wait(self.semaphore, timeout)
            // fetch moments happened before endTime
            // make API request to upload the photo
            let url:NSURL = NSURL(string: SharingManager.Constant.fetchMomentsURL)!
            let session = NSURLSession.sharedSession()
            let request = NSMutableURLRequest(URL: url)
            request.HTTPMethod = "POST"
            request.cachePolicy = NSURLRequestCachePolicy.ReloadIgnoringCacheData
            request.addValue("application/json; charset=utf-8", forHTTPHeaderField: "Content-Type")
            request.addValue("application/json", forHTTPHeaderField: "Accept")
            
            var param: [String: AnyObject] = ["content_type": "JSON"]
            
            // specify the time condition for the request
            if publishedEarlier == true {
                param["published_earlier_than"] = true
            }
            if publishedLater == true {
                param["published_later_than"] = true
            }
            
            
            var existingMomentID: [Int] = []
            for moment in self.moments {
                existingMomentID.append(moment.id!)
            }
            param["existing_moments_id"] = existingMomentID
            
            do {
                request.HTTPBody = try NSJSONSerialization.dataWithJSONObject(param, options: .PrettyPrinted)
            } catch {
                print("error serializing JSON: \(error)")
            }
            
            UIApplication.sharedApplication().networkActivityIndicatorVisible = true
            if spinner != nil {
                dispatch_async(dispatch_get_main_queue(), {
                    spinner!.startAnimating()
                })
            }
            
            let task = session.dataTaskWithRequest(request) {
                (let data, let response, let error) in
                
                guard let _:NSData = data, let _:NSURLResponse = response  where error == nil else {
                    print("error: \(error)")
                    return
                }
                
                do {
                    let momentsJSON = try NSJSONSerialization.JSONObjectWithData(data!, options: .AllowFragments) as! NSArray
                    var tempMoments = [Moment]()
                    
                    for moment in momentsJSON {
                        
                        let tempMoment = Moment()
                        if let id = moment["id"] as? Int {
                            tempMoment.id = id
                        }
                        if let username = moment["username"] as? String {
                            tempMoment.username = username
                        }
                        if let description = moment["description"] as? String {
                            tempMoment.description = description
                        }
                        if let pub_time_interval = moment["pub_time_interval"] as? NSTimeInterval {
                            tempMoment.pub_time_interval = pub_time_interval
                        }
                        if let thumbnail_base64 = moment["thumbnail_base64"] as? String {
                            tempMoment.thumbnail_base64 = thumbnail_base64
                        }
                        tempMoments.append(tempMoment)
                    }
                    if tempMoments.count > 0 {
                        print(tempMoments[0].pub_time_interval)
                    }
                    dispatch_async(dispatch_get_main_queue(), {
                        if publishedLater == true {
                            self.moments = tempMoments + self.moments
                        } else {
                            self.moments += tempMoments
                        }
                    })
                } catch {
                    print("error serializing JSON: \(error)")
                }
                dispatch_async(dispatch_get_main_queue(), {
                    UIApplication.sharedApplication().networkActivityIndicatorVisible = false
                    if spinner != nil {
                        spinner!.stopAnimating()
                    }
                    if refreshControl != nil {
                        refreshControl?.endRefreshing()
                    }
                })
                dispatch_semaphore_signal(self.semaphore)
            }
            task.resume()
        }
    }
}
