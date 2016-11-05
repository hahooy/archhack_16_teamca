//
//  UploadViewController.swift
//  CataractTracker
//
//  Created by Yongzheng Huang on 11/5/16.
//  Copyright © 2016 JYLock. All rights reserved.
//

import UIKit

class UploadViewController: UIViewController {


    @IBOutlet weak var titleInput: UITextField!
    @IBOutlet weak var descriptionInput: UITextView!
    @IBOutlet weak var imageView: UIImageView!
    var imageToSubmit: UIImage?

    override func viewDidLoad() {
        super.viewDidLoad()
        self.view.backgroundColor = backgroundColorDarker
        imageView.image = imageToSubmit
    }
    
    /*
    @IBAction func dismissKeyboard(sender: UITapGestureRecognizer) {
        descriptionInput.resignFirstResponder()
    }
     */
    


    // upload the photo
    @IBAction func uploadPhoto(sender: UIBarButtonItem) {
        
        // quit if no photo available for upload
        guard let image = imageView.image else {
            return
        }
        
        // compress and encode the image
        
        //let thumbnail = UIImageJPEGRepresentation(image.getThumbnail(SharingManager.Constant.maxThumbnailSize), 0)!
        let originalPhoto = UIImageJPEGRepresentation(image, 0)!
        let params = [
            "title": titleInput.text!,
            "description": descriptionInput.text!
        ]
        let boundary = generateBoundaryString()
        
        // make API request to upload the photo
        let url:NSURL = NSURL(string: SharingManager.Constant.uploadMomentURL)!
        let session = NSURLSession.sharedSession()
        let request = NSMutableURLRequest(URL: url)
        request.HTTPMethod = "POST"
        request.cachePolicy = NSURLRequestCachePolicy.ReloadIgnoringCacheData
        request.setValue("multipart/form-data; boundary=\(boundary)", forHTTPHeaderField: "Content-Type")
        request.HTTPBody = createBodyWithParameters(params, filePathKey: "uploadedfile", imageDataKey: originalPhoto, boundary: boundary)
        
        let task = session.dataTaskWithRequest(request) {
            (let data, let response, let error) in
            
            guard let _:NSData = data, let _:NSURLResponse = response  where error == nil else {
                print("error: \(error)")
                return
            }
            
            do {
                let json = try NSJSONSerialization.JSONObjectWithData(data!, options: .AllowFragments) as! NSDictionary
                if let message = json["msg"] as? String {
                    print(message)
                    dispatch_async(dispatch_get_main_queue(), {
                        
                    })
                }
            } catch {
                print("error serializing JSON: \(error)")
            }
        }
        task.resume()
        
        
        // return to the last page
        // let viewControlers = navigationController?.viewControllers
        // navigationController?.popToViewController(viewControlers![0], animated: true)
        navigationController?.popViewControllerAnimated(true)
    }
    
    func createBodyWithParameters(parameters: [String: String]?, filePathKey: String?, imageDataKey: NSData, boundary: String) -> NSData {
        let body = NSMutableData();

        if parameters != nil {
            for (key, value) in parameters! {
                body.appendData("--\(boundary)\r\n".dataUsingEncoding(NSUTF8StringEncoding)!)
                body.appendData("Content-Disposition: form-data; name=\"\(key)\"\r\n\r\n".dataUsingEncoding(NSUTF8StringEncoding)!)
                body.appendData("\(value)\r\n".dataUsingEncoding(NSUTF8StringEncoding)!)
            }
        }
        
        let filename = "user-profile.jpg"
        let mimetype = "image/jpg"
        
        body.appendData("--\(boundary)\r\n".dataUsingEncoding(NSUTF8StringEncoding)!)
        body.appendData("Content-Disposition: form-data; name=\"\(filePathKey!)\"; filename=\"\(filename)\"\r\n".dataUsingEncoding(NSUTF8StringEncoding)!)
        body.appendData("Content-Type: \(mimetype)\r\n\r\n".dataUsingEncoding(NSUTF8StringEncoding)!)
        body.appendData(imageDataKey)
        body.appendData("\r\n".dataUsingEncoding(NSUTF8StringEncoding)!)
        body.appendData("--\(boundary)--\r\n".dataUsingEncoding(NSUTF8StringEncoding)!)
        
        return body
    }
    
    func generateBoundaryString() -> String {
        return "Boundary-\(NSUUID().UUIDString)"
    }
}

extension UIImage {
    func getThumbnail(maxThumbnailSize: CGFloat) -> UIImage {
        let reduceRatio = min(maxThumbnailSize / self.size.height, maxThumbnailSize / self.size.width, 1.0)
        let thumbnailWidth = self.size.width * reduceRatio
        let thumbnailHeight = self.size.height * reduceRatio
        let imageView = UIImageView(frame: CGRect(x: 0, y: 0, width: thumbnailWidth, height: thumbnailHeight))
        imageView.contentMode = .ScaleAspectFit
        imageView.image = self
        UIGraphicsBeginImageContextWithOptions(imageView.bounds.size, false, 1)
        imageView.layer.renderInContext(UIGraphicsGetCurrentContext()!)
        let resizedImage = UIGraphicsGetImageFromCurrentImageContext()
        UIGraphicsEndImageContext()
        return resizedImage!
    }
}
