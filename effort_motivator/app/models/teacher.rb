class Teacher < ActiveRecord::Base
  has_many :groups
  
  has_secure_password

  before_save :create_remember_token

  validates : name, presence: true, length: { maximum: 50 }
  validates : password, presence: true, length: { minimum: 6}

  private 
  
    def create_remember_token
      self.remember_token = SecureRandom.urlsafe_base64
    end
end
